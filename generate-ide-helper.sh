#!/bin/bash

echo "Going to generate the IDE helper files..."
echo ""
echo "Generating the IDE helper main file"
php8.1 artisan ide-helper:generate
echo ""
echo "Generating the IDE helper models file."
php8.1 artisan ide-helper:models --nowrite
echo ""
echo "Generating the IDE helper meta file."
php8.1 artisan ide-helper:meta