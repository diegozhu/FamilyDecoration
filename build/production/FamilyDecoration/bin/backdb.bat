@echo off 
setlocal enabledelayedexpansion
title backup

rem �����ļ���
for /f "tokens=1 delims=/ " %%j in ("%date%") do set d1=%%j
for /f "tokens=2 delims=/ " %%j in ("%date%") do set d2=%%j
for /f "tokens=3 delims=/ " %%j in ("%date%") do set d3=%%j
for /f "tokens=1 delims=: " %%j in ("%time%") do set t1=%%j
for /f "tokens=2 delims=: " %%j in ("%time%") do set t2=%%j
for /f "tokens=3 delims=:. " %%j in ("%time%") do set t3=%%j
set dirName=%d1%-%d2%-%d3%-%t1%-%t2%-%t3%
cd ../backups/
mkdir %dirName%
cd %dirName%

rem �������ݿ� �� �ϴ������ű�
mysqldump -hlocalhost -uroot  familydecoration  > database.sql
rem svn add .
cd ../..
move jobs/update.bat backups/%dirName% > nul 2>&1

rem ����svn
svn update

rem ִ�б��������ű�
jobs/update.bat >nul 2>&1

rem ��ȡ��Ҫ���ݵ�pdf �б�
cd backups/%dirName%
..\..\bin\cur -s "http://127.0.0.1/fd/libs/budget.php?action=listIds" > budgetIds.txt
rem svn add budgetIds.txt

rem ����pdf�����ļ���
mkdir backup-pdf

rem ����curl ��ȡpdf
for /f %%a in ('type budgetIds.txt') do (
	set line=%%a
	set url="http://127.0.0.1/fd/fpdf/index2.php?budgetId=!line:~0,25!"
	..\..\bin\cur -s !url! > !line:~26!
)

rem �ƶ���pdf �����ļ���
move *.pdf backup-pdf/

cd backup-pdf
rem svn add .
cd ..

rem �ص�rootĿ¼
cd ../..

rem �ύ��Ҫ�ύsvn������
rem svn commit -m "normal backup"

rem �ص�ԭ��binĿ¼
cd bin/