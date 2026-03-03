## Izveidojam tēmu aplikācijai

1. Failā app.css, kas atroda resources/css, pievienojam krāsu paleti

```css
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
    'Segoe UI Symbol', 'Noto Color Emoji';
    --color-background: okch(0.12 0 0);
    --color-foreground: okch(0.95 0 0);
    --color-card: okch(0.16 0.0);
    --color-primary: okch(0.65 0.15 160);
    --color-primary-foreground: okch(0.12 0 0);
    --color-border: okch(0.24 0 0);
    --color-input: okch(0.24 0 0);
    --color-muted-foreground: okch(0.6 0 0);
```
2. Izveido css mapē jaunu mapi ar nosaukumu components
3. Izveido tur failus btn.css un form.css

```css
    button {
    cursor: pointer;
}

.btn {
    background: var(--color-primary);
    border-radius: var(--radius-xl);
    color: var(--color-primary-foreground);
    padding-inline: calc(var(--spacing) * 3);
    font-size: var(--text-sm);
    font-weight: var(--font-weight-medium);
    cursor: pointer;
    height: calc(var(--spacing) * 8);
    line-height: calc(var(--spacing) * 8);
    display: inline-block;
}

.btn.btn-outlined {
    background: transparent;
    border: 1px solid var(--color-border);
    border-color: var(--color-border);
    color: var(--color-foreground);
}

.btn.btn-outlined:hover {
    background: color-mix(in srgb, black 25%, var(--color-input));
}

.btn.btn-ghost {
    background: transparent;
}

.btn:has(> svg) {
    display: flex;
    align-items: center;
    column-gap: calc(var(--spacing) * 2);
}

.btn:hover {
    background: color-mix(in srgb, black 10%, var(--color-primary));
    text-decoration: none;
}
```

```css
label {
    color: var(--color-foreground);
}

.input {
    border-radius: var(--radius-md);
    height: calc(var(--spacing) * 10);
    width: 100%;
    border-width: 1px;
    border-color: var(--color-border);
    padding: calc(var(--spacing) * 2) calc(var(--spacing) * 3);
    background-color: var(--color-card);
    color: var(--color-foreground);
    outline: 2px solid transparent;
    outline-offset: 2px;
}

.input::placeholder {
    color: var(--color-muted-foreground);
}

.input:focus-visible {
    outline: 0;
    box-shadow:
        0 0 0 calc(var(--spacing) * .5) var(--color-background)
        0 0 0 calc(var(--spacing) * 1) var(--color-primary);
}

@media (min-width: var(--breakpoint-md)) {
    .input {
        font-size: var(--text-sm);
    }
}

.textarea {
    border-radius: var(--radius-md);
    height: calc(var(--spacing) * 40);
    width: 100%;
    border-width: 1px;
    border-color: var(--color-border);
    padding: calc(var(--spacing) * 2) calc(var(--spacing) * 3);
    background-color: var(--color-card);
    color: var(--color-foreground);
    outline: 2px solid transparent;
    outline-offset: 2px;
}

.textarea::placeholder {
    color: var(--color-muted-foreground);
}

.textarea:focus-visible {
    outline: 0;
    box-shadow:
        0 0 0 calc(var(--spacing) * .5) var(--color-background)
        0 0 0 calc(var(--spacing) * 1) var(--color-primary);
}

.label {
    display: block;
    font-size: var(--text-sm);
}

input[type=file] {
    display: block;
    width: 100%;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: var(--color-foreground);
}

input[type=file]::file-selector-button {
    margin-right: calc(var(--spacing) * 4);
    padding: calc(var(--spacing) * 2) calc(var(--spacing) * 4);
    border-radius: calc(var(--spacing) * 2);
    border-width: 0;
    font-size: 0.875rem;
    line-height: 1.25rem;
    font-weight: 500;
    background-color: var(--color-primary);
    color: rgb(var(--color-background));
}

input[type=file]::file-selector-button:hover {
    opacity: 0.9;
}

.error {
    font-size: var(--text-sm);
    color: var(--color-red-600);
}

.form-muted-icon {
    color: var(--color-muted-foreground);
}

.form-muted-icon:hover {
    color: var(--color-foreground);
}
```

4. Pievienojam app.css komponentes

```css
@import './components/btn.css' layer(components);
@import './components/form.css' layer(components);
```


6. Mapē views izveidojam mapi components/layout/layout.blade.php

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Idea</title>
  @vite(['resources/css/app.css'])
</head>
<body class="bg-background text-foreground">
  <p>Hello World</p>
</body>
</html>
```

7. Mapē views failā welcome.blade.php, parādam layout, lai redzētu ka nav nekādu kļūdu

```html
<x-layout>

</x-layout>
```

8. npm run build