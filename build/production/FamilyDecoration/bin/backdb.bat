@echo off 
setlocal enabledelayedexpansion
title backup

echo 开始备份

rem ´´½¨ÎÄ¼þ¼Ð
for /f "tokens=1 delims=/ " %%j in ("%date%") do set d1=%%j
for /f "tokens=2 delims=/ " %%j in ("%date%") do set d2=%%j
for /f "tokens=3 delims=/ " %%j in ("%date%") do set d3=%%j
for /f "tokens=1 delims=: " %%j in ("%time%") do set t1=%%j
for /f "tokens=2 delims=: " %%j in ("%time%") do set t2=%%j
for /f "tokens=3 delims=:. " %%j in ("%time%") do set t3=%%j
set dirName=%d1%-%d2%-%d3%-%t1%-%t2%-%t3%
cd ../backups/

rem ±¸·ÝÊý¾Ý¿â ºÍ ÉÏ´ÎÉý¼¶½Å±¾
mysqldump -hlocalhost -uroot  familydecoration  > database.sql
svn add database.sql > nul 2>&1

mkdir %dirName%

cd ../
move jobs/update.bat backups/%dirName% > nul 2>&1

rem ¸üÐÂsvn
svn update -q

rem Ö´ÐÐ±¾´ÎÉý¼¶½Å±¾
jobs/update.bat >nul 2>&1

rem »ñÈ¡ÐèÒª±¸·ÝµÄpdf ÁÐ±í
cd backups/%dirName%
..\..\bin\cur -s "http://127.0.0.1:82/familydecoration/build/production/FamilyDecoration/libs/budget.php?action=listIds" > budgetIds.txt
rem svn add budgetIds.txt

rem ´´½¨pdf±¸·ÝÎÄ¼þ¼Ð
mkdir backup-pdf
mkdir backup-xls

rem ±éÀúcurl »ñÈ¡pdf
for /f %%a in ('type budgetIds.txt') do (
	set line=%%a
	set url1="http://127.0.0.1:82/familydecoration/build/production/FamilyDecoration/fpdf/index2.php?budgetId=!line:~0,25!"
	set url2="http://127.0.0.1:82/familydecoration/build/production/FamilyDecoration/phpexcel/index.php?budgetId=!line:~0,25!"
	set fileName1="!line:~26!.pdf"
	set fileName2="!line:~26!.xls"
	..\..\bin\cur -s !url1! >  !fileName1!
	..\..\bin\cur -s !url2! >  !fileName2!
)


rem ÒÆ¶¯µ½pdf ±¸·ÝÎÄ¼þ¼Ð
move *.pdf backup-pdf/ > nul 2>&1
move *.xls backup-xls/ > nul 2>&1


cd ../
svn commit ./database.sql -m backup -q

rem »Øµ½rootÄ¿Â¼
cd ../bin/

echo 备份成功