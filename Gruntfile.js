module.exports = function(grunt) {

    var target = grunt.option('env') || 'dev';
    var tasks;

    // 1. All configuration goes here 
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            dist: {
                src: [
                    'addons/emeehan/themes/custom/js/modules/*.js',
                    'addons/emeehan/themes/custom/js/mylibs/*.js',
                    'addons/emeehan/themes/custom/js/default.js'
                ],
                dest: 'production.js',
            }
        },
        uglify: {
            build: {
                src: 'production.js',
                dest: 'production.min.js'
            }
        },
        imagemin: {
            dynamic: {
                files: [{
                    expand: true,
                    cwd: 'addons/emeehan/themes/custom/img',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: 'build/'
                }]
            }
        },
        // cachebust: {
        //     build: {
        //         files: {
        //             expand: true,
        //             //cwd: 'test',
        //             src: ['test.html'],
        //             dest: 'test/'
        //         }
        //     }
        // }
        // asset_cachebuster: {
        //     options: {
        //         //buster:
        //         //htmlExtension:
        //         //ignore:
        //     },
        //     build: {
        //         files: {
        //             src: ['test.html'],
        //             dest: ['test/test.html']
        //         }
        //     }
        // }
        cacheBust: {
            options: {
              encoding: 'utf8',
              algorithm: 'md5',
              length: 8,
              deleteOriginals: true
            },
            assets: {
              files: [
                  {
                    expand: true,
                    cwd: 'test/',
                    src: ['*.html'],
                    dest: 'test/'
                  }
              ]
            }
        },
        clean: {
            build: {
                noWrite: true,
                src: 'test/'
            }
        }

    });

    // 3. Where we tell Grunt we plan to use this plug-in.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-cache-bust');
    //grunt.loadNpmTasks('grunt-cachebust');
    //grunt.loadNpmTasks('grunt-asset-cachebuster');

    // 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
    //grunt.registerTask('default', ['concat', 'uglify', 'cacheBust']);
    if (target === 'prod') {
        tasks = ['concat', 'uglify', 'clean', 'cacheBust'];
    }else{
        tasks = ['concat', 'uglify', 'clean', 'cacheBust'];
    }
    
    grunt.registerTask('default', tasks);

};