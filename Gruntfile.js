module.exports = function(grunt) {

    var target = grunt.option('env') || 'dev';
    var tasks;
    var timestamp = new Date().getTime();

    grunt.initConfig({});
    // CONCAT
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.config('concat',{
        emeehan: {
            src: [
                'addons/emeehan/themes/custom/js/mylibs/*.js',
                'addons/emeehan/themes/custom/js/default.js'
            ],
            dest: 'addons/emeehan/themes/custom/publish/js/production.js',
        }
    });
    // UGLIFY
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.config('uglify',{
        emeehan: {
            src: 'addons/emeehan/themes/custom/publish/js/production.js',
            dest: 'addons/emeehan/themes/custom/publish/js/production.min.js'
        }
    });
    // IMAGEMIN
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.config('imagemin',{
        emeehan: {
            files: [{
                expand: true,
                cwd: 'addons/emeehan/themes/custom/img/',
                src: ['**/*.{png,jpg,gif}'],
                dest: 'addons/emeehan/themes/custom/publish/img/'
            }]
        }
    });
    // CLEAN
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.config('clean',{
        emeehan: {
            src: [
                'addons/emeehan/themes/custom/publish/',
                'addons/emeehan/themes/custom/views/partials/publish/'
            ]
        }
    });
    // // CACHE BUST
    // grunt.loadNpmTasks('grunt-cache-bust');
    // grunt.config('cacheBust',{
    //     options: {
    //         encoding: 'utf8',
    //         algorithm: 'md5',
    //         length: 12,
    //         deleteOriginals: true,
    //         replaceTerms: ['{{theme:path}}'],
    //         ignorePatterns: ['prettify.js','default.js']
    //     },
    //     emeehan: {
    //         files: [{
    //             baseDir: './',
    //             //cwd: 'addons/emeehan/themes/custom/views/partials/',
    //             src: ['addons/emeehan/themes/custom/views/partials/footer-scripts.html']
    //         }]
    //     }
    // });
    // REPLACE TEXT
    grunt.loadNpmTasks('grunt-text-replace');
    grunt.config('replace',{
        emeehan: {
            src: ['addons/emeehan/themes/custom/views/partials/footer-scripts.html','addons/emeehan/themes/custom/views/partials/metadata.html'],
            overwrite: true,
            replacements: [
                {
                    from: '.min.js',
                    to: '.min.' + timestamp + '.js'
                },
                {
                    from: '.min.css',
                    to: '.min.' + timestamp + '.css'
                }
            ]
        }
    });
    // COPY
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.config('copy',{
        emeehan: {
            files: [
                {
                    expand: true,
                    cwd: 'addons/emeehan/themes/custom/views/partials/',
                    src: ['metadata.html', 'footer-scripts.html'],
                    dest: 'addons/emeehan/themes/custom/views/partials/publish/'
                },
                {
                    expand: true,
                    cwd: 'addons/emeehan/themes/custom/css/',
                    src: ['style.css','prettify.css'],
                    dest: 'addons/emeehan/themes/custom/publish/css/'
                }
            ]
        }
    });

    if (target === 'prod') {
        tasks = ['clean', 'copy', 'concat', 'uglify'];
    }else{
        tasks = ['clean', 'copy', 'concat', 'uglify', 'imagemin'];
    }
    
    grunt.registerTask('default', tasks);

};