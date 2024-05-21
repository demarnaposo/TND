rsync -e 'ssh -p 8122' -avrz tnde@103.14.229.60:/home/tnde/public_html/assets /home/tnd/aplikasi-tnd/

sleep 5

rsync -e 'ssh -p 8122' -avrz tnde@103.14.229.60:/home/tnde/public_html/uploads /home/tnd/aplikasi-tnd/
