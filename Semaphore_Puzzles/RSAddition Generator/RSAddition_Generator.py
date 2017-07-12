import random
from datetime import datetime
import time

#Prime Functions/Maths
def CheckPrime(n):
    for c in range(2,n):
        if n%c==0:
            return False
    return True
def GetFactors(num):
    numList = []
    for c in range(2,num+1):
        if num%c==0:
            numList.append(c)
    return numList
def Check_CoPrime(num1,num2):
    numList1=GetFactors(num1)
    numList2=GetFactors(num2)
    for c in range(0,len(numList1)):
        for i in range(0,len(numList2)):
            if(numList1[c]==numList2[i]):
                return False
    return True

#Cryptography mathematics
def FindE(m):#For the puzzle include the assumption that e is as small as possible as this is what the function does
    for c in range(2,m):
        if(Check_CoPrime(m,c)):
            return c

def FindD(e,m):#This algorithm is lazy and does not use the extended eucledian algorithm but still works �\_(?)_/�
    d=1
    while True:
        if((d*e)%m==1):
            return d
        d+=1

def ModularExponentiation(n,e,mod):#So we can get answsers to those BIG numbers
    if mod==1:
        return 0
    ans = 1
    for c in range(1,e+1):
        ans=(ans*n)%mod
    return ans

#Puzzle generation
def GeneratePuzzleString():
    generatedEncryptionSuccessfully=False
    p=0
    q=0
    mainMsg=random.randint(3,100)
    encryptedMsg=""
    while generatedEncryptionSuccessfully==False:
        generatedQ=False
        generatedP=False
        while generatedP==False:
            p=random.randint(3,200)
            if CheckPrime(p):
                generatedP=True
        while generatedQ==False:
            q=random.randint(3,200)
            if CheckPrime(q) and (p!=q):
                generatedQ=True
        n=p*q
        m=(p-1)*(q-1)
        e=FindE(m)
        d=FindD(e,m)
        encryptedMsg=ModularExponentiation(mainMsg,e,n)
        decryptedMsg=ModularExponentiation(encryptedMsg,d,n)
        if(decryptedMsg!=mainMsg):
            continue
        else:
            break
    finalStr=str(p)+","+str(q)+","+str(encryptedMsg)
    return finalStr,mainMsg
#File handling
def CreatePuzzle(n):#n determines how long the puzzle is going to be
    puzzleFile = open("primes.txt","w")
    fullAns=0
    percentage=0
    totalPercentage=0
    for c in range(0,n):
        percentage=round((c/n)*100,1)
        totalPercentage=round((percentage/200)*100,2)
        print("Progress creating:"+str(percentage)+"%       Total progress:"+str(totalPercentage)+"%")
        strToWrite,ans=GeneratePuzzleString()
        if c!=1000:
            puzzleFile.writelines(strToWrite+"\n")
        else:
            puzzleFile.writelines(strToWrite)
        fullAns+=ans
    puzzleFile.close()
    percentageToReturn=totalPercentage
    return fullAns,percentageToReturn

print('''
RSAddition Puzzle Creation Tool for semaphore.io
Script by Charles.
''')

writtenPuzzle=False
totalProgress=0
amountOfLines=int(input("How many lines do you want the puzzle to be?:"))
while not writtenPuzzle:
    print("Starting timer...")
    startTime=datetime.now()
    print("Writing puzzle..")
    fullAns,percentReached = CreatePuzzle(amountOfLines)
    print("Finished writing puzzle! The answer to the puzzle is:" + str(fullAns) +"\nNow checking puzzle.")
    puzzleFile=open("primes.txt","r")
    allLines = puzzleFile.readlines()
    numofoops=0
    total=0
    for c in range(0,len(allLines)):
        percentage=round((c/len(allLines))*100,1)
        percentageSum=percentReached+(percentage/2)
        totalProgress=round((percentageSum),1)
        print("Progress checking:"+str(percentage)+"%       Total progress:"+str(totalProgress)+"%")
        line = allLines[c]
        nums=line.split(',')
        p=int(nums[0])
        q=int(nums[1])
        encryptedMsg=int(nums[2])
        n=(p*q)
        m=(p-1)*(q-1)
        e=FindE(m)
        d=FindD(e,m)
        decryptedMsg=ModularExponentiation(encryptedMsg,d,n)
        reEncryptedMsg=ModularExponentiation(decryptedMsg,e,n)
        total+=decryptedMsg
        if reEncryptedMsg!=encryptedMsg:
            numofoops+=1
    if(numofoops==0 and total==fullAns):
        print("The math all checks out! Puzzle is ready to be distributed!")
        writtenPuzzle=True
    else:
        print("The math DOES NOT check out. Generating a new puzzle...")
        time.sleep(1000)
timeTaken = datetime.now()-startTime
print("Answer to puzzle:"+str(fullAns))
print("Total time taken:"+str(timeTaken))
