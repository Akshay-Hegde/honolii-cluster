module.exports = function(grunt) {

    /* -------------------------------------------------
    *
    *  Variables and Data
    * 
    --------------------------------------------------*/
    var timestamp = new Date().getTime();

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json')
    });
    grunt.config('util',{
        site: null,
        
    });
    grunt.config('build', {
        emeehan: [
            {
                src: [
                    "addons/emeehan/themes/custom/js/mylibs/*.js",
                    "addons/emeehan/themes/custom/js/default.js"
                ],
                dest: 'addons/emeehan/themes/custom/publish/js/production.js'
            },
            {
                src: [
                    "addons/emeehan/themes/custom/css/style.css",
                    "addons/emeehan/themes/custom/css/prettify.css"
                ],
                dest: 'addons/emeehan/themes/custom/publish/css/production.css'
            }
        ],
        jasc_awh: [
            {
                src: [
                    "addons/jasc_awh/themes/custom/js/mylibs/*.js",
                    "addons/jasc_awh/themes/custom/js/default.js"
                ],
                dest: 'addons/jasc_awh/themes/custom/publish/js/production.js'
            },{
                src: [
                    "addons/jasc_awh/themes/custom/css/foundation.css",
                    "addons/jasc_awh/themes/custom/css/app.css"
                ],
                dest: 'addons/jasc_awh/themes/custom/publish/css/production.css'
            }
        ],
        jasc_core: [
            {
                src: [
                    "addons/jasc_core/themes/custom/js/libs/*.js",
                    "addons/jasc_core/themes/custom/js/mylibs/*.js",
                    "addons/jasc_core/themes/custom/js/default.js"
                ],
                dest: 'addons/jasc_core/themes/custom/publish/js/production.js'
            },{
                src: [
                    "addons/jasc_core/themes/custom/css/style.css"
                ],
                dest: 'addons/jasc_core/themes/custom/publish/css/production.css'
            }
        ],
        mxtb_core: [
            {
                src: [
                    "addons/mxtb_core/themes/custom/js/libs/bootstrap-lightbox.js",
                    "addons/mxtb_core/themes/custom/js/jquery.imagesloaded.js",
                    "addons/mxtb_core/themes/custom/js/default.js"
                ],
                dest:'addons/mxtb_core/themes/custom/publish/js/production.js',
            },{
                src: [
                    "addons/mxtb_core/themes/custom/css/style.css",
                    //"addons/mxtb_core/themes/custom/css/datepicker.css"
                ],
                dest:'addons/mxtb_core/themes/custom/publish/css/production.css',
            }
        ],
        ncesa_core: [
            {
                src: [
                    "addons/ncesa_core/themes/custom/js/mylibs/jquery.cookie.js",
                    "addons/ncesa_core/themes/custom/js/main.js",
                ],
                dest:'addons/ncesa_core/themes/custom/publish/js/production.js',
            },{
                src: [
                    "addons/ncesa_core/themes/custom/css/screen.css",
                    "addons/ncesa_core/themes/custom/css/main.css"
                ],
                dest:'addons/ncesa_core/themes/custom/publish/css/production.css',
            },{
                src: [
                    "addons/ncesa_core/themes/custom/css/mobile.css"
                ],
                dest:'addons/ncesa_core/themes/custom/publish/css/mobile.production.css',
            },{
                src: [
                    "addons/ncesa_core/themes/custom/css/print.css"
                ],
                dest:'addons/ncesa_core/themes/custom/publish/css/print.production.css',
            }
        ],
        // wetumka_core: {
        //     js: [],
        //     css: []
        // }
    });

    /* -------------------------------------------------
    *
    *  Tasks Config and Targets
    * 
    --------------------------------------------------*/

    // CONCAT
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.config('concat',{
        build: { files:[]} // dynamic built in task functions
    });
    // UGLIFY
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.config('uglify',{
        build: {
            sourceMap: true,
            expand: true,
            cwd: 'addons/<%= util.site %>/themes/custom/publish/js/',
            src: '*.js',
            //overwrite: true
            dest: 'addons/<%= util.site %>/themes/custom/publish/js-' + timestamp + '/'
        }
    });
    // CSSMIN
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.config('cssmin',{
        build: {
            expand: true,
            cwd: 'addons/<%= util.site %>/themes/custom/publish/css/',
            src: '*.css',
            dest: 'addons/<%= util.site %>/themes/custom/publish/css-' + timestamp + '/'
        }
    });
    // IMAGEMIN
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.config('imagemin',{
        build: {
            expand: true,
            cwd: 'addons/<%= util.site %>/themes/custom/img/',
            src: ['**/*.{png,jpg,gif,ico}'],
            dest: 'addons/<%= util.site %>/themes/custom/publish/img/'
        }
    });
    // CLEAN
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.config('clean',{
        build: {
            src: [
                'addons/<%= util.site %>/themes/custom/publish/',
                'addons/<%= util.site %>/themes/custom/views/partials/publish/',
                'addons/<%= util.site %>/themes/custom/views/web/partials/publish/',
                'addons/<%= util.site %>/themes/custom/views/mobile/partials/publish/'
            ]
        }
    });
    // REPLACE TEXT
    grunt.loadNpmTasks('grunt-text-replace');
    grunt.config('replace',{
        build: {
            src: [
                'addons/<%= util.site %>/themes/custom/views/partials/publish/*.html',
                'addons/<%= util.site %>/themes/custom/views/web/partials/publish/*.html',
                'addons/<%= util.site %>/themes/custom/views/mobile/partials/publish/*.html'
            ],
            expand: true,
            overwrite: true,
            replacements: [
                {
                    from: '/publish/js/',
                    to: '/publish/js-' + timestamp + '/'
                },
                {
                    from: '/publish/css/',
                    to: '/publish/css-' + timestamp + '/'
                }
            ],
        }
    });
    // COPY
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.config('copy',{
        build: {
            files:[
                {
                    expand: true,
                    cwd: 'addons/<%= util.site %>/themes/custom/views/partials/',
                    src: ['metadata.html', 'footer-scripts.html'],
                    dest: 'addons/<%= util.site %>/themes/custom/views/partials/publish/'
                },
                {
                    expand: true,
                    cwd: 'addons/<%= util.site %>/themes/custom/views/web/partials/',
                    src: ['metadata.html', 'footer-scripts.html'],
                    dest: 'addons/<%= util.site %>/themes/custom/views/web/partials/publish/'
                },
                {
                    expand: true,
                    cwd: 'addons/<%= util.site %>/themes/custom/views/mobile/partials/',
                    src: ['metadata.html', 'footer-scripts.html'],
                    dest: 'addons/<%= util.site %>/themes/custom/views/mobile/partials/publish/'
                }
            ]
        }
    });

    /* -------------------------------------------------
    *
    *  Tasks
    * 
    --------------------------------------------------*/
    //grunt.registerTask('default', ['clean', 'copy','concat', 'uglify','cssmin','replace']);
    //grunt.registerTask('prod', ['clean', 'concat', 'uglify']);

    grunt.registerMultiTask('build', 'Build task sites - build:site-name', function() {
        grunt.log.ok("Building " + this.target);
        grunt.config.set('util.site', this.target);
        
        // CLEAN
        grunt.task.run('clean:build');
        // CONCAT
        grunt.config.set('concat.build.files', this.data);        
        grunt.task.run([
            'concat:build',
            'uglify:build',
            'cssmin:build',
            'imagemin:build',
            'copy:build',
            'replace:build'
        ]);
    });

};