BesiBau WordPress Theme
=======================

ONE-CLICK INSTALL
1. WordPress Dashboard > Appearance > Themes > Add New > Upload Theme.
2. Choose besibau-theme.zip and click Install Now.
3. Click Activate.
Done. The full site (header, all sections, footer) is live. No page builder, no extra plugins.

ELEMENTOR HEADER AND FOOTER
The theme has editable Elementor header/footer support now.

Option A, Elementor Pro:
Create a Header or Footer in Elementor Theme Builder and assign it. The theme will use it automatically.

Option B, Elementor free:
1. Elementor > Templates > Saved Templates > Add New.
2. Create a Section or Container template for the header/footer.
3. Appearance > Customize > BesiBau Elementor.
4. Choose the saved header and footer templates.

If no Elementor template is selected, the theme uses its built-in PHP header and footer.

GITHUB AUTOMATIC UPDATES
This theme includes a GitHub release updater and a GitHub Actions workflow.

Setup:
1. Create a GitHub repository for the theme.
2. In style.css, replace:
   Update URI: https://github.com/babatrodon/BesiBauWordpressTheme
   with your real GitHub repository URL.
3. Push the theme to the main branch.

Every push to main creates a new GitHub release named like v1.0.1.23 and uploads
besibau-theme.zip. WordPress checks that release and shows a normal theme update
in Dashboard > Updates.

Important: the GitHub repository should be public for the built-in updater. Private
repositories need extra authentication handling on the WordPress side.

EDIT YOUR DETAILS (one place)
Open functions.php and edit the besibau_info() array near the top: phone, email,
addresses, and your Facebook / Instagram / LinkedIn links. Save and re-upload, or edit
via Appearance > Theme File Editor.

YOUR LOGO
By default a clean text logo "BesiBau" is shown. To use your image logo:
Appearance > Customize > Site Identity > Logo. Upload it once. It appears in the header.

SWAP THE PHOTOS FOR YOUR OWN
All images live in the folder assets/img/. Replace any file with your own photo using
the SAME file name and it appears automatically. Files:
  hero.jpg     large hero background
  about1.jpg   left "About" image (tall)
  about2.jpg   small overlapping "About" image
  why.jpg      "Warum BesiBau" background
  trio.jpg     "Versprechen" background
  cta.jpg      "Offerte" band background
  proj1..6.jpg the six project cards
Easiest way: Appearance > Theme File Editor is not for images, so use FTP or your
host's File Manager to upload into wp-content/themes/besibau-theme/assets/img/.

CONTACT FORM
The form sends to your email via WordPress. If your host does not send mail reliably,
install a free SMTP plugin (for example "WP Mail SMTP") and connect your mailbox.

MENU
The navigation is a one-page menu that scrolls to each section (Home, Über Uns,
Dienstleistungen, Unser Team, Unsere Arbeit, Kontakt). To change labels or order,
edit the $besibau_nav array in template-parts/header-default.php.

COLOURS
Gold #BA9056, dark #20232E. To adjust, edit the :root variables at the top of style.css.
