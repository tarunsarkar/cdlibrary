import sys

# initialize

try:
    param1 = sys.argv[1]
except:
    print("Error: Input file parameter missing!")
    exit(1)

try:
    param2 = sys.argv[2]
except:
    print("Error: Output file parameter missing!")
    exit(1)

# Read input file contents and parse
AllCDs = []
newCD = {}
newCD['Tracks'] = []
fIn = open(param1, "r")
bNewCD = False
bArtistFound = False
bTitleFound = False
while True:
    try:
        tLine = fIn.readline()
        if len(tLine) == 0:
            print("INFO: Reached EOF!")
            # Add last CD's complete information
            AllCDs.append(newCD)
            break
        tLine = tLine.strip('\n')
        print("LineContent:" + tLine)
        if len(tLine) == 0:
            bNewCD = False
            bArtistFound = False
            bTitleFound = False
            # Add a CD's complete information
            AllCDs.append(newCD)
            newCD = {}
            newCD['Tracks'] = []
        else:
            if bNewCD is False:
                bNewCD = True
            if bArtistFound is False:
                bArtistFound = True
                newCD['Artist'] = tLine
            elif bTitleFound is False:
                bTitleFound = True
                newCD['Title'] = tLine
            else:
                newTrack = {
                    'Track': tLine
                }
                newCD['Tracks'].append(newTrack)
    except:
        print("Error: Reading input file!")
        break

fIn.close()

# Prepare XML
fOut = open(param2, "w")
tLine = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
fOut.writelines(tLine)
tLine = "<cdcatalog xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"result.xsd\">"
fOut.writelines(tLine)
for CD in AllCDs:
    tLine = "<cd>"
    fOut.writelines(tLine)
    tLine = "<artist>" + CD['Artist'] +"</artist>"
    fOut.writelines(tLine)
    tLine = "<title>" + CD['Title'] +"</title>"
    fOut.writelines(tLine)
    tLine = "<tracks>"
    fOut.writelines(tLine)
    for Track in CD['Tracks']:
        tLine = "<track>" + Track['Track'] + "</track>"
        fOut.writelines(tLine)
    tLine = "</tracks>"
    fOut.writelines(tLine)
    tLine = "</cd>"
    fOut.writelines(tLine)

tLine = "</cdcatalog>"
fOut.writelines(tLine)
fOut.close()
