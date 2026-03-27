@echo off
cd /d c:\Users\1\Desktop\Webnewbiz\frontend
call npx ng build 2>&1
echo EXIT_CODE=%ERRORLEVEL%
