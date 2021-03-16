@echo off


set/p option=Please enter the mysql installation address:
set/p host=Please enter the link address :
set/p user=Please enter user :
set/p password=Please enter the password :
set/p database=Please enter the database to be backed up :

set back_dir=back_up_file
if exist %cd%\%back_dir% (
    echo %cd%\%back_dir%\ Folder already exists
) else (
    md %cd%\%back_dir%
)

echo ^@echo off > ./backed_up.bat
echo if %%time:~0,2%% leq 9 (set hours=0%%time:~1,1%%) else (set hours=%%time:~0,2%%) >> ./backed_up.bat
echo set filename=%cd%\%back_dir%\%%date:~3,4%%%%date:~8,2%%%%date:~11,2%%%%hours%%%%time:~3,2%%%%time:~6,2%%.sql >> ./backed_up.bat
echo echo Data is being backed up, please wait... >> ./backed_up.bat
echo call %option%\mysqldump -h%host% -u%user%  -p%password% --databases %database% ^> %%filename%% >> ./backed_up.bat
