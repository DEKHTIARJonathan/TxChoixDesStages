from __future__ import print_function
from __future__ import unicode_literals

from BaseHTTPServer import BaseHTTPRequestHandler, HTTPServer
from smartcard.scard import *
import smartcard.util
import json

__version__ = 1

nfc_reader = None

class Reader():
    """
	    Manipulate the nfc reader
    """
    def __init__(self):
        print("Reader() get_context...")
        self.init_context()
        self.init_reader()

    def read_badge(self):
        badge_id = None
        while not badge_id:
            try:
                hresult, hcard, dwActiveProtocol = SCardConnect(self.context, self.reader,
                    SCARD_SHARE_SHARED, SCARD_PROTOCOL_T0 | SCARD_PROTOCOL_T1)
                if hresult != SCARD_S_SUCCESS:
                    raise Exception('Unable to connect: ' +
                        SCardGetErrorMessage(hresult))
                print('Connected with active protocol', dwActiveProtocol)

                try:
                    hresult, response = SCardTransmit(hcard, dwActiveProtocol,
                        [0xFF, 0xCA, 0x00, 0x00, 0x00])
                    if hresult != SCARD_S_SUCCESS:
                        raise Exception('Failed to transmit: ' +
                            SCardGetErrorMessage(hresult))
                    badge_id = smartcard.util.toHexString(response, smartcard.util.HEX).replace("0x","").split(" ")
                    badge_id.pop()
                    badge_id.pop()
                    badge_id = "".join(badge_id)
                    print('Find badge: ' + badge_id)
                finally:
                    hresult = SCardDisconnect(hcard, SCARD_UNPOWER_CARD)
                    if hresult != SCARD_S_SUCCESS:
                        raise Exception('Failed to disconnect: ' +
                            SCardGetErrorMessage(hresult))
                    print('Disconnected')
            except:
                pass
        return badge_id

    def init_context(self):
        hresult, hcontext = SCardEstablishContext(SCARD_SCOPE_USER)
        if hresult != SCARD_S_SUCCESS:
            raise Exception('Failed to establish context : ' +
                SCardGetErrorMessage(hresult))
        print('Context established!')
        self.context = hcontext

    def init_reader(self):
        print("Search readers...")
        hresult, readers = SCardListReaders(self.context, [])
        if hresult != SCARD_S_SUCCESS:
            raise Exception('Failed to list readers: ' +
                SCardGetErrorMessage(hresult))
        print('PCSC Readers:', readers)

        if len(readers) < 1:
            raise Exception('No smart card readers')

        self.reader = readers[0]
        print("Using reader:", self.reader)

    def release_context(self):
        hresult = SCardReleaseContext(self.context)
        if hresult != SCARD_S_SUCCESS:
            raise Exception('Failed to release context: ' +
                    SCardGetErrorMessage(hresult))
        print('Released context.')


class MyRequestHandler(BaseHTTPRequestHandler):
    """
	    Request handler
    """
    def do_GET(self):
        global nc_reader
        try:
            if self.path.endswith("/badge"):
                self.send_response(200)
                self.send_header("Content-type", "application/json")
                self.send_header("Access-Control-Allow-Origin", "*")
                self.end_headers()
                self.wfile.write(json.dumps({"badge_id": nfc_reader.read_badge()}))
                return
            elif self.path.endswith("/version"):
                self.send_response(200)
                self.send_header("Content-type", "application/json")
                self.send_header("Access-Control-Allow-Origin", "*")
                self.end_headers()
                self.wfile.write(json.dumps({"version": __version__}))
                return
            else:
                self.send_error(404, "Api Not Found %s" % self.path)

        except Exception as e:
            print(str(e))
            self.send_error(404, "File Not Found %s" % self.path)

    def do_POST(self):
        self.do_GET() # currently same as post, but can be anything
 
def main():
    try:
        global nfc_reader
        nfc_reader = Reader()
        server = HTTPServer(("", 8080), MyRequestHandler)
        print("starting httpserver...")
        server.serve_forever()
    except KeyboardInterrupt:
        print("^C received, shutting down server")
        server.socket.close()
        nfc_reader.release_context()
 
if __name__ == "__main__":
    main()
