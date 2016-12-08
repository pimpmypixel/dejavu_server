import matplotlib.pyplot as plt
import numpy as np
import wave
import sys


filename = '/mnt/Beijing/AddRemoverFiles/Reklamer/tv2/single/skousen.mp3'#os.path.join(request.folder, 'static', 'sounds', 'adg3.mp3')
spf = wave.open(filename,'r')

# first I'll open the audio file
#sound = AudioSegment.from_mp3(filename)

#Extract Raw Audio from Wav File
signal = spf.readframes(-1)
signal = np.fromstring(signal, 'Int16')
fs = spf.getframerate()

#If Stereo
if spf.getnchannels() == 2:
    print 'Just mono files'
    sys.exit(0)


Time=np.linspace(0, len(signal)/fs, num=len(signal))

plt.figure(1)
plt.title('Signal Wave...')
plt.plot(Time,signal)
plt.show()