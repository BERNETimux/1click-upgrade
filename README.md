# 1click-upgrade

Installation:
1. You need a local wamp to run the index.php
2. Copy the files to the root - OUTSIDE - of your target website directory.
for Example (root = www of localhost)

root/mywebsite.com/typo3conf/ext/myextension   (inside TYPO3)
root/1click-upgrade   			     	 (put the files there)
root/www.anotherwebsite.com

Upgrade:
1. Important! BACKUP first your extension you want to upgrade! The upgrader will directly overwrites the target, without any warning.
2. Access to the script on your wamp. Example: "localhost/1click-upgrade"
3. Enter the path to your extension. Example  "yourwebsite.com/typo3conf/ext/myextension"
4. Click on "Upgrade"
5. If the script tells you something to fix please do this now.

Testing: (after upgrade)
1. Login to the Typo3 Backend. Empty caches of TYPO3 in the Backend. Your Extension has a good chance to work now.
2. Test your extension very good. (FE/BE)
3. If nessesary fix the last problems




