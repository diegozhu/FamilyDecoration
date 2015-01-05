@echo off 
setlocal enabledelayedexpansion
title backup

rem 创建文件夹
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

rem 备份数据库 和 上次升级脚本
mysqldump -hlocalhost -uroot  familydecoration  > database.sql
rem svn add .
cd ../..
move jobs/update.bat backups/%dirName% > nul 2>&1

rem 更新svn
svn update

rem 执行本次升级脚本
jobs/update.bat >nul 2>&1

rem 获取需要备份的pdf 列表
cd backups/%dirName%
..\..\bin\cur -s "http://127.0.0.1/fd/libs/budget.php?action=listIds" > budgetIds.txt
rem svn add budgetIds.txt

rem 创建pdf备份文件夹
mkdir backup-pdf

rem 遍历curl 获取pdf
for /f %%a in ('type budgetIds.txt') do (
	set line=%%a
	set url="http://127.0.0.1/fd/fpdf/index2.php?budgetId=!line:~0,25!"
	..\..\bin\cur -s !url! > !line:~26!
)

rem 移动到pdf 备份文件夹
move *.pdf backup-pdf/

cd backup-pdf
rem svn add .
cd ..

rem 回到root目录
cd ../..

rem 提交需要提交svn的内容
rem svn commit -m "normal backup"

rem 回到原来bin目录
cd bin/