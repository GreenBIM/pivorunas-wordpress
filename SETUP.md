# Set this up on WordPress

You need a normal WordPress site (6.5 or newer) and permission to add a plugin and a theme. This preview cannot be WordPress by itself.

Two folders have to be installed. The plugin holds the work. The theme only draws it. That split is the WordPress rule: if someone later changes the theme, the photographs, films, and books stay.

## 1. Copy the folders

On the server, or in the host’s file manager:

- Copy `pivorunas-portfolio` into `wp-content/plugins/`
- Copy `pivorunas` into `wp-content/themes/`

Or, in WP Admin, upload each folder as a zip. Two zips are included next to this file: `pivorunas-portfolio.zip` and `pivorunas.zip`.

- Plugins, Add New, Upload Plugin, choose `pivorunas-portfolio.zip`
- Appearance, Themes, Add New, Upload Theme, choose `pivorunas.zip`

## 2. Turn them on

1. Plugins, activate **Pivorunas Portfolio**.
2. Appearance, Themes, activate **Pivorunas**.
3. Settings, Permalinks, choose **Post name**, and save. This makes the addresses `/photos/`, `/videos/`, and `/publications/`.

If you use WP-CLI on the server:

```bash
wp plugin activate pivorunas-portfolio
wp theme activate pivorunas
wp rewrite structure '/%postname%/' --hard
wp rewrite flush
```

## 3. Make the pages

The photo, video, and book lists already have addresses. You do not make those as pages.

Make three ordinary pages. Pages, Add New. Use these slugs, or the menu will not match the site:

| Page title | Slug |
|---|---|
| About | about |
| Contact | contact |
| Links | links |

Paste the text into the page. Links such as the email address can be ordinary links. Do not add a contact form. WordPress treats a form as a separate plugin, and this site does not use one.

## 4. Make the menu

Appearance, Menus, create a menu, and assign it to **Main menu**.

Add, in this order:

1. Photos, the custom link to `/photos/` (or the Photos archive)
2. Videos, `/videos/`
3. About
4. Publications, `/publications/`
5. Contact
6. Links

On the front page, Photos sits on the photograph. The other items sit on the white side. The current item turns gray.

## 5. Add the work

Open **Portfolio** in the admin sidebar.

**Home photographs.** Choose the two pictures that crossfade on the front page. The first one shows first. Save.

**Photos, Add series.**

- Title, for example SUNRISE.
- Featured image. This is what the side list shows on hover.
- Order. A smaller number sits higher in the side list.
- Frame shape.
- Add photographs, in the order they should be seen. One file is one frame.
- On each file in the Media Library, the Caption is the line under the picture. Leave it blank and the site uses the title plus the number, such as `SUNRISE #4`.

**Videos, Add film.**

- Title.
- Featured image, the still before play.
- Choose a video file. If you skip the file, the still drifts when someone presses play.
- Check “This film has audio” only when it does.
- Order, same idea as the photo list.

**Books, Add book.**

- Title.
- Volume number. The list sorts by that number.

## 6. The name

Appearance, Customize, Site Identity, **Name in the header**.

The first letter uses Noto Sans. The rest uses Ancizar Sans Light, and its top lines up with that first letter. Saving the customizer does not change those two fonts. Those live in the theme.

## 7. Change the type without code

These classes are in `pivorunas/assets/css/site.css`:

| Class | What it is |
|---|---|
| `.title` | ABOUT, PHOTOS, BOOKS |
| `.body` | Page text |
| `.menu` | Top menu |
| `.menu.is-active` | The current top item, gray |
| `.menu-side` | The lists beside photos and videos |
| `.menu-side.is-active` | The current item in those lists |

Colors are also in `theme.json`, so Appearance, Editor, Styles can change the palette.

To change the look and still be able to update this theme later, make a child theme. Do not edit the parent theme’s files on the live site.

## 8. Check it the WordPress way

From the site directory, if WP-CLI and PHP_CodeSniffer are installed:

```bash
wp theme list
wp plugin list
wp post list --post_type=series --fields=ID,post_title,menu_order
```

The official style check is PHP_CodeSniffer with the WordPress rules. The official theme check is the Theme Check plugin. This package is a private theme and plugin for this site. It is not submitted to the WordPress.org directory.
