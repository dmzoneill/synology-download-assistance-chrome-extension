#!/usr/bin/env bash
# Regenerate the en_GB and en_US locale catalogues from the canonical en catalogue.
# They are identical copies; edit synologydownload/_locales/en/messages.json and re-run this script.
set -euo pipefail

root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
locales="${root}/synologydownload/_locales"

for locale in en_GB en_US; do
	cp "${locales}/en/messages.json" "${locales}/${locale}/messages.json"
done
