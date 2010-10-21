#!/bin/sh
# Met à jour le dépot et le site en même temps comme ça vous vous faites pas chier.
# Utilisez le, c'est bien.
git push origin master
ssh bd@ceypasbien.com -p 1022 1>/dev/null 2>/dev/null <<END
cd public_html
git pull
exit
END
