import os
from pydub import *
import StringIO
from pydub import AudioSegment
try:
    from PIL import Image
    from PIL import ImageDraw
    from PIL import ImageDraw2
    from PIL import ImageFilter
except AttributeError: #module has no attribute ImageN
    import Image
    import ImageDraw
    import ImageFont

if __name__ == '__main__':
    img_width = 1170
    img_height = 140
    line_color = 180
    filename = '/mnt/Beijing/AddRemoverFiles/Reklamer/tv2/single/skousen.mp3'#os.path.join(request.folder, 'static', 'sounds', 'adg3.mp3')

    # first I'll open the audio file
    sound = AudioSegment.from_mp3(filename)

    # break the sound 180 even chunks (or however
    # many pixels wide the image should be)
    chunk_length = len(sound) / img_width

    loudness_of_chunks = [
        sound[i * chunk_length: (i + 1) * chunk_length].rms
        for i in range(img_width)
    ]
    max_rms = float(max(loudness_of_chunks))
    scaled_loudness = [round(loudness * img_height / max_rms) for loudness in loudness_of_chunks]

    # now convert the scaled_loudness to an image
    im = Image.new('L', (img_width, img_height), color=255)
    draw = ImageDraw.Draw(im)
    for x, rms in enumerate(scaled_loudness):
        y0 = img_height - rms
        y1 = img_height
        draw.line((x, y0, x, y1), fill=line_color, width=1)
    buffer = StringIO.StringIO()
    del draw
    im = im.filter(ImageFilter.SMOOTH).filter(ImageFilter.DETAIL)
    im.save(buffer, 'PNG')
    buffer.seek(0)
    response.stream(buffer, filename=filename + '.png')