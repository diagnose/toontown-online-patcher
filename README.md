# Toontown Launcher Patcher
Server files for handling a login via the original Toontown Online launcher.


## Prerequisites:
* Your own registration page
* The *Disney's Toontown Online* launcher
* A database with the following fields:

| users      | login_attempts |
|------------|----------------|
| ID         | ID             |
| Username   | Username       |
| Password   | IP             |
| Ranking    | Location       |
| Banned     |
| TestAccess |
| Verified   |

## Instructions:
* Create a file in your Toontown Launcher directory named "parameters.txt"
* Inside of the text document, add the following line:

```
PATCHER_BASE_URL=http://yourwebsitehere.com/launcher/current
```

**WARNING:** These files were created almost two years ago and are vulnerable to SQL injection, and tokens are not random/secured. Also, sending login parameters via GET leaves plaintext passwords in the access logs. This is how Disney set up their launcher and it cannot be changed. This launcher is a hacky way to boot up the original Toontown online or connect to a custom OTP server, however this should **NOT** be used by private servers, unless you want to see a PSA pop up from @jjkoletar ;)
