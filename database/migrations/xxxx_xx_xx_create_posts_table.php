Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('teaser');
    $table->timestamps();
});
