import MySQLdb
import getpass
from getpass import getpass
import sys
import os
import pysftp as sftp

__author__ = 'Charles'
IPAddress="192.168.0.10"
DBName="WebsiteTesting"
DBUser="puzzleUploader"


print("Puzzle uploader for my website.\nScript by Charles c:")
option=str(input("Do you need to upload a file(s) as part of the puzzle?"))
fileDirs=[]
if option.lower()=="y":
    fileAmount=input("How many files are there to upload?")
    for c in range(0,int(fileAmount)):
        fileDir=str(input("Dir of file " + str((c+1))+":"))
        fileDirs.append(fileDir)
    print("All files selected and ready for upload.")
name=input("Puzzle name:")
topic=input("Puzzle topic:")
difficulty=input("Puzzle difficulty:")
print("Puzzle description:(Multi-Line Input, press enter on blank line to end)")
description=""
allLines=[]
while True:
    line = input('')
    if line == '':
        break
    allLines.append(line)
description='\n'.join(allLines)
answer=input("Answer:")
jackpot=input("Jackpot:")
author=input("User ID of author:")
print("Ready to upload to server!")
password_SQL = input("For added confirmation, please enter the SQL password for the " + str(DBUser) + " account:")
password_SFTP=""
if option.lower()=="y":
    password_SFTP=input("For added confirmation, please enter the SFTP password for charles:")
print("Starting MYSQL sequence")
db=MySQLdb.connect(host=IPAddress,user=DBUser,passwd=password_SQL,db=DBName,port=3306)
print("Connected to MYSQL Server")
db.autocommit(True)
cursor=db.cursor()
cursor.execute("INSERT INTO competitions (Puzzle_Title,Puzzle_Topic,Puzzle_Difficulty,puzzle_description,Puzzle_Answer,Puzzle_Jackpot,Puzzle_Author) VALUES (%s,%s,%s,%s,%s,%s,%s);",(name,topic,difficulty,description,int(answer),int(jackpot),int(author)))
db.commit()
db.close()
print("SQL Query successfully executed!")
if option.lower()=="y":
    print("Starting SFTP upload sequence...")
    opts = sftp.CnOpts()
    opts.hostkeys=None
    connection=sftp.Connection(host=IPAddress,username='charles',password=password_SFTP,cnopts=opts)
    remotePath = '/var/www/html/competition/puzzles/'
    for c in range(0,len(fileDirs)):
        print("Uploading file " + str(c+1) + " out of " + str(len(fileDirs)))
        fileName=os.path.basename(fileDirs[c])
        pathInsertion=remotePath+fileName
        connection.put(fileDirs[c],pathInsertion)
    connection.close()

print("Finished. Competition Uploaded. :)")
print("Press enter to exit.")
input()
