=== Post Presentations ===
Contributors: jancbeck
Tags: presentation, html5
Requires at least: 3.6
Tested up to: 3.6
Stable tag: 0.1
License: MIT license
License URI: http://opensource.org/licenses/mit-license.php

Turns your WordPress posts into reveal.js powered presentations. 

== Description ==

This plugin is intended to create simple slideshow presentations from the WordPress posts. It is inspired from the [Slides theme be Otto](ottopress.com/2013/slides-a-presentation-theme/) but rather than turning your whole blog into one slideshow this plugin simply adds an alternative display mode for each posts. 

Place `<!--nextslide-->` in your posts to split your posts into individual slides.

This plugin is still in its early version. Don't expect this to not break in extreme cases. It should support common HTML though including images, video and audio.

Future updates may include:

- Adding a first slide that contains the post title, author, date and more info.
- A button directly in the the TinyMCE editor similar to the more tag to add the `<!--nextslide-->` tag.
- More options to the settings page.
- Individual settings for each post.
- More support for reveal.jus features (e.g. PDF export)
- Perhaps getting rid of the `<!--nextslide-->` tag by auto-splitting on h2 headings?

More ideas? Post them to the support forum!

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload plugin to the plugin directory (e.g. `/wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' menu in the WordPress dashboard
3. *Optionally:* Configure slideshow options such as the active **theme** on the plugin settings page (to be found under 'Options' → 'Post Presentations').
4. Show a presentation of any post or page by appending `/presentation/` to its permalink. E.g. `http://example.com/sample-post/presentation/`

== Frequently Asked Questions ==

= What's the purpose of this plugin? =

I used WordPress to write down notes for client project presentations and didn't want to use an external presentation tool to basically just copy & paste my content into slides. On the other hand using a plain article for presentation is not very pretty. 
The idea is that this plugin gives you both: write simple note-style articles and present them to somebody without any big extra work. It won't interfere with your normal theme or force you to change the way you structure your content other than using the - otherwise invisible - `<!--nextslide-->` tag. 

= When should I use this plugin instead of Ottos *Slides* theme? =

The Slide theme by Otto forces you to basically turn your whole WordPress installation into a content database for a slideshow presentation with customly arranged page content, site name etc.

Perhaps you are already using a special theme to manage projects with WordPress or you just have a normal blog that you want to extend with a presentation feature. Then this plugin might be better suited for you will not have to change anything except adding the invisible `<!--nextslide-->` commment tags to your posts.

This comes with the disadvantage of lesser features though. E.g. vertical slides (sub-slides) or slide specific options are not possible with this plugin. 

== Changelog ==

= 0.1 =
Initial version

== Upgrade Notice ==

= 0.1 =
Initial version
