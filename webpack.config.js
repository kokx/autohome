const path = require('path');
module.exports = {
    entry: {
        tempcontrol: './src/OpenTherm/js/tempcontrol.js',
        sensorchart: './src/Device/js/sensorCharts.js'
    },
    output: {
        path: path.resolve(__dirname, 'public/js'),
        publicPath: '/js/',
        filename: "[name].bundle.js"
    },
    devServer: {
        contentBase: './js',
        port: 8000,
        // proxy requests to PHP dev backend
        proxy: {
            '/': 'http://localhost:8080'
        }
    },
    resolve: {
        extensions: ['*', '.js', '.jsx']
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader"
                }
            }
        ]
    },
    // plugins: []
};
