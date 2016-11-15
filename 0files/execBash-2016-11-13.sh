
cp ../voanews/*.jpg ./ 

curl -o voafile0.mp3 http://gandalf.ddo.jp/mp3/161113.mp3

mv voafile0.mp3 aux_voafile0.mp3

avconv -y -i 'aux_voafile0.mp3' -acodec libmp3lame -b:a 64k -ac 1 -ar 44100 'voafile0.mp3'

curl -o voafile1.mp3 http://downdb.51voa.com/201611/technology-in-college-classrooms.mp3

mv voafile1.mp3 aux_voafile1.mp3

avconv -y -i 'aux_voafile1.mp3' -acodec libmp3lame -b:a 64k -ac 1 -ar 44100 'voafile1.mp3'

curl -o voafile2.mp3 http://downdb.51voa.com/201611/words-and-their-stories-military-words.mp3

mv voafile2.mp3 aux_voafile2.mp3

avconv -y -i 'aux_voafile2.mp3' -acodec libmp3lame -b:a 64k -ac 1 -ar 44100 'voafile2.mp3'

curl -o voafile3.mp3 http://downdb.51voa.com/201611/3d-device-lets-museum-visitors-see-hidden-treasures.mp3

mv voafile3.mp3 aux_voafile3.mp3

avconv -y -i 'aux_voafile3.mp3' -acodec libmp3lame -b:a 64k -ac 1 -ar 44100 'voafile3.mp3'

curl -o voafile4.mp3 http://downdb.51voa.com/201611/two-belarusian-men-invent-simple-prosthetic-arm.mp3

mv voafile4.mp3 aux_voafile4.mp3

avconv -y -i 'aux_voafile4.mp3' -acodec libmp3lame -b:a 64k -ac 1 -ar 44100 'voafile4.mp3'

curl -o voafile5.mp3 http://downdb.51voa.com/201611/supporting-community-justice-in-the-dominican-republic.mp3

mv voafile5.mp3 aux_voafile5.mp3

avconv -y -i 'aux_voafile5.mp3' -acodec libmp3lame -b:a 64k -ac 1 -ar 44100 'voafile5.mp3'

convert COVER_mp3.jpg  -font Arial -pointsize 52 -draw "gravity north fill black  text 0,230 '2016-11-13' fill yellow  text 1,234 '2016-11-13' " cover_mp3.jpg

mp3wrap aux.mp3 voafile{0..5}.mp3

lame -m m -b 64 aux_MP3WRAP.mp3 --id3v2-only --ignore-tag-errors --ta 'Internet' --tt 'VoaNews 2016-11-13' --ti 'cover_mp3.jpg' VoaNews-2016-11-13.mp3

rm aux* voafile* cover_mp3.jpg

convert COVER_book.jpg  -font Arial -pointsize 52 -draw "gravity north fill black  text 10,270 '2016-11-13' fill yellow  text 12,274 '2016-11-13' " cover_book.jpg

pandoc texto.txt -o aux_texto.md --parse-raw

pandoc texto.txt -o  VoaNews-2016-11-13.epub --epub-cover-image=cover_book.jpg  --toc

rm texto.txt  aux_texto.md *.jpg 
