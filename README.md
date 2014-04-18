billetterieUTC
==============

daemon.py
---------
This script is a standalone python http server.
It depends on pyscard : http://pyscard.sourceforge.net/

You should have a nfc reader, compatible with PCSC.

api.php
-------
This files expose the server. Manage auth, selling etc...

index.php/general.js
--------------------
Is the client side. Everything is javascript/Jquery and communicate with api.php (and daemon.py for reading smartcard)


