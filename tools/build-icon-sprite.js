#!/usr/bin/env node
/**
 * VETRA Icon Pack — sprite builder.
 *
 * Reads `assets/icons/vetra-icons.json` (the single source of truth) and writes
 * a standalone SVG sprite to `assets/icons/vetra-icons.svg`. Run with:
 *
 *   node tools/build-icon-sprite.js
 *
 * The PHP renderer uses the same JSON, so the sprite and inline output can
 * never drift out of sync.
 */
'use strict';

const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const source = path.join(root, 'assets', 'icons', 'vetra-icons.json');
const target = path.join(root, 'assets', 'icons', 'vetra-icons.svg');

const library = JSON.parse(fs.readFileSync(source, 'utf8'));

function layer(icon, key) {
  return Array.isArray(icon[key]) ? icon[key].join('') : '';
}

function build() {
  const parts = [];
  parts.push('<?xml version="1.0" encoding="UTF-8"?>');
  parts.push(`<!-- VETRA Icon Pack v${library.version} — generated from vetra-icons.json, do not edit by hand. -->`);
  parts.push('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="vetra-icon-sprite" aria-hidden="true">');

  for (const [name, icon] of Object.entries(library.icons)) {
    parts.push(`\t<symbol id="vetra-icon-${name}" viewBox="${library.viewBox}">`);
    parts.push(`\t\t<g class="vetra-icon-base" stroke="currentColor" fill="none">${layer(icon, 'base')}</g>`);
    const accent = layer(icon, 'accent');
    if (accent) {
      parts.push(`\t\t<g class="vetra-icon-accent" stroke="#C67D34" fill="none">${accent}</g>`);
    }
    parts.push('\t</symbol>');
  }

  parts.push('</svg>');
  parts.push('');

  return parts.join('\n');
}

fs.writeFileSync(target, build(), 'utf8');
const count = Object.keys(library.icons).length;
console.log(`VETRA Icon Pack v${library.version}: wrote ${count} symbols to ${path.relative(root, target)}`);
