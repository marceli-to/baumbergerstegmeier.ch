#!/bin/bash
# Pixel difference (AE, fuzz 2 %) of the screenshots two shots.js runs share
for f in "$1"/*.png; do
  n=$(basename "$f")
  [ -f "$2/$n" ] || { echo "missing  $n"; continue; }
  d=$(magick compare -metric AE -fuzz 2% "$f" "$2/$n" null: 2>&1 | cut -d' ' -f1)
  s1=$(magick identify -format "%wx%h" "$f"); s2=$(magick identify -format "%wx%h" "$2/$n")
  echo "$d $s1 $s2 $n"
done | sort -rn
