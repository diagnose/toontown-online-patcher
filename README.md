# Toontown Launcher Patcher
Server files for handling a login via the original Toontown Online launcher.

## Instructions:
* Create a file in your Toontown Launcher directory named "patcher.txt"
* Inside of the text document, add the following line:

```
PATCHER_BASE_URL=http://yourwebsitehere.com
```

**WARNING:** The files for handling logins were created almost two years ago and are vulnerable to SQL injection. Also, sending login parameters via GET leaves plaintext passwords in the access logs. This is how Disney set up their launcher and it cannot be changed.