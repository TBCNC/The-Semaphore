import random


__author__ = 'Charles'


def GenerateSerial(name):
    totalSerial=0
    for c,i in enumerate(name):
        totalSerial+=pow(ord(i),(c+1))
    totalSerial=int(totalSerial/((pow(len(name),len(name)))))
    return totalSerial

print("Powerful name puzzle generator")
amount = int(input("How many random names do you want?:"))
fileName = input("Filename:")
file = open(fileName,"w")
allSerials = []
for c in range(0,amount):
    progress = round((c/amount)*100,3)
    print("Progress:"+str(progress))
    charLength = random.randint(7,13)
    fullName = ""
    for i in range(0,charLength):
        fullName+=chr(random.randint(65,126))
    allSerials.append(GenerateSerial(fullName))
    file.write(fullName+"\n")
print(sum(allSerials))