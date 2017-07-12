import random
from PIL import Image
from PIL import ImageDraw

def SetupImage(width, height,fileName):
    size = (width, height)
    im = Image.new('RGB', size)
    draw=ImageDraw.Draw(im)
    for c in range(0,width):
        for i in range(0, height):
            pointPos = (c, i)
            color = ()
            while True:
                redColour = random.randint(0,random.randint(5,255))
                blueColour = random.randint(0,random.randint(5,255))
                greenColour = random.randint(0,random.randint(5,255))
                colour = (redColour,blueColour,greenColour)
                fullColStr = str(colour[0])+str(colour[1])+str(colour[2])
                if not Palindrome(fullColStr):
                    break
            draw.point(pointPos,fill=colour)
    del draw
    im.save(fileName,'PNG')

def Palindrome(num):
    return str(num)==str(num)[::-1]

def CreateColourfulPalindrome():
    fullPalindrome=""
    while True:
        fullPalindrome=""
        for c in range(0,6):
            fullPalindrome+=str(random.randint(1,9))
        if Palindrome(fullPalindrome):
            break
    return fullPalindrome

def SplitColours(palindrome):
    r=palindrome[0:2]
    g=palindrome[2:4]
    b=palindrome[4:6]
    return (int(r),int(g),int(b))

def SetPalindromes(fileName,nPalindromes):
    im=Image.open(fileName)
    draw=ImageDraw.Draw(im)
    width,height=im.size
    usedPositions=[]
    imLoad = im.load()
    for c in range(0,nPalindromes):
        print("Palindrome Progress:"+str((c/nPalindromes)*100)+"%")
        pointpos=(0,0)
        while True:
            pointpos = (random.randint(0,width-1),random.randint(0,height-1))
            if pointpos not in usedPositions:
                usedPositions.append(pointpos)
                break
        colourPalindrome=CreateColourfulPalindrome()
        coloursSplit=SplitColours(colourPalindrome)
        imLoad[pointpos[0],pointpos[1]]=coloursSplit
    im.save(fileName)

def ReadImage(imageFile):
    im = Image.open(imageFile)
    allRGBs = []
    width, height = im.size
    for c in range(0,width):
        for i in range(0,height):
            pointPos = (c,i)
            colour = im.getpixel(pointPos)
            allRGBs.append(colour)
    return allRGBs

def GetPalindromeAmount(rgbs):
    totalPalindromes=0
    for c in range(0,len(rgbs)):
        totalStr=""
        for i in range(0,len(rgbs[c])):
            totalStr+=str(rgbs[c][i])
        if Palindrome(totalStr):
            totalPalindromes+=1
    return totalPalindromes




width = int(input("Width of image:"))
height = int(input("Height of image:"))
fileName = str(input("Filename:"))
palindromeAmount = int(input("Amount of palindromes [0 for random]:"))
if palindromeAmount==0:
    palindromeAmount=random.randint(10,int((width*height)/10))
print("--FINAL SETTINGS--")
print("Width:"+str(width))
print("Height:"+str(height))
print("Filename:"+str(fileName))
print("Palindrome Amount:"+str(palindromeAmount))
print("\n")
print("Please wait while your puzzle is generated...This may take some time depending on the resolution of the image\n")
print("Setting up image...")
SetupImage(width,height,fileName)
print("Set up image. Placing palindromes...")
SetPalindromes(fileName,palindromeAmount)
print("Created image successfully!")



print("Reading image...")
rgbs = ReadImage(fileName)
print("Read image! Determining amount of palindromes...")
palindromes = GetPalindromeAmount(rgbs)
print("Done! There are " + str(palindromes) + " palindromes.")
print("We set " + str(palindromeAmount) + " palindromes.")

