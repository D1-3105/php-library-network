function markdown_to_html(markdown) {
    var converter = new showdown.Converter();
    return converter.makeHtml(markdown);
}