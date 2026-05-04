# Accessibility checklist (WCAG 2.1 AA oriented)

Use this before merging UI work.

## Structure & semantics

- [ ] One logical `<h1>` per page where appropriate; headings don’t skip levels without reason.
- [ ] Landmark regions are meaningful (`<main>`, `<nav>`, `<header>`, `<footer>`).
- [ ] Every interactive control is a `<button>`, `<a href>`, or native control—not a `<div>` with `@click` unless keyboard-equivalent and role are handled.

## Keyboard & focus

- [ ] All custom controls are reachable with Tab / Shift+Tab.
- [ ] Focus visibility meets **2.4.7 Focus Visible** (e.g. `focus-visible:ring`).
- [ ] Modals trap focus while open and restore focus on close (this project: `@alpinejs/focus` + `<x-admin.modal>`).

## Forms

- [ ] Each input has an associated `<label>` or `aria-label` / `aria-labelledby`.
- [ ] Errors use `role="alert"` or live regions where appropriate; hints use `aria-describedby`.

## Images & icons

- [ ] Meaningful images have descriptive `alt`; decorative images use `alt=""` and/or `aria-hidden="true"` on SVG decorations.

## Disclosure / menus (Alpine)

- [ ] Toggle buttons expose `aria-expanded` and `aria-controls` pointing at stable IDs.
- [ ] Hidden panels use `display: none` when closed (`x-show` / `x-cloak`) so focus doesn’t enter hidden UI.

## Dialogs

- [ ] Dialog has `role="dialog"`, `aria-modal="true"`, and **`aria-labelledby`** (visible title) or **`aria-label`** (slot-only pattern).

## Testing tools

- **axe DevTools** (browser extension).
- **Lighthouse** Accessibility category (Chrome DevTools).
- **VoiceOver** (macOS: Cmd+F5), **NVDA** (Windows), or **TalkBack** (Android).
