@echo off
Set APPLICATION_ENV=development
@echo on
c:\php\php.exe -d variables_order=EGPCS -S 0.0.0.0:80 -t public public/builtin-ws-wrapper.php
