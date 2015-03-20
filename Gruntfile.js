module.exports = function(grunt) {

    var target = grunt.option('env') || 'dev';
    var tasks;

    grunt.initConfig({});
    // CONCAT
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.config('concat',{
        build: {
            src: [
                'addons/emeehan/themes/custom/js/modules/*.js',
                'addons/emeehan/themes/custom/js/mylibs/*.js',
                'addons/emeehan/themes/custom/js/default.js'
            ],
            dest: 'test/production.js',
        }
    });
    // UGLIFY
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.config('uglify',{
        build: {
            src: 'test/production.js',
            dest: 'test/production.min.js'
        }
    });
    // IMAGEMIN
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.config('imagemin',{
        build: {
            files: [{
                expand: true,
                cwd: 'addons/emeehan/themes/custom/img',
                src: ['**/*.{png,jpg,gif}'],
                dest: 'build/'
            }]
        }
    });
    // CLEAN
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.config('clean',{
        build: {
            src: 'test/'
        }
    });
    // CACHE BUST
    grunt.loadNpmTasks('grunt-cache-bust');
    grunt.config('cacheBust',{
        options: {
            encoding: 'utf8',
            algorithm: 'md5',
            length: 8,
            deleteOriginals: true
        },
        build: {
            files: [{
                expand: true,
                cwd: 'test/',
                src: '*.html'
            }]
        }
    });
    // COPY
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.config('copy',{
        build: {
            expand: true,
            cwd: 'test-pt2/',
            src: '*.html',
            dest: 'test/'
        }
    });

    if (target === 'prod') {
        tasks = ['clean', 'copy', 'concat', 'uglify', 'cacheBust'];
    }else{
        tasks = ['clean', 'copy', 'concat', 'uglify', 'cacheBust'];
    }
    
    grunt.registerTask('default', tasks);

};