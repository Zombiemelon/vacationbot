const HtmlWebPackPlugin = require("html-webpack-plugin");
const webpack = require('webpack');
const dotenv = require('dotenv');

const htmlPlugin = new HtmlWebPackPlugin({
    template: "./index.html",
    filename: "./index.html",
});

module.exports = () => {
    const env = dotenv.config().parsed;

    // reduce it to a nice object, the same as before
    const envKeys = Object.keys(env).reduce((prev, next) => {
        prev[`process.env.${next}`] = JSON.stringify(env[next]);
        return prev;
    }, {});

    return {
        output: {
            //for proper work of react-router-dom
            publicPath: '/'
        },
        devServer: {
            historyApiFallback: true,
        },
        module: {
            rules: [
                {
                    test: /\.(js|jsx)$/,
                    resolve: { extensions: [".js", ".jsx"] },
                    exclude: /node_modules/,
                    use: {
                        loader: "babel-loader"
                    }
                },
                {
                    test: /\.css$/,
                    use: [
                        {
                            loader: "style-loader"
                        },
                        {
                            loader: "css-loader",
                            options: {
                                modules: false,
                                importLoaders: 2,
                                sourceMap: true,
                                minimize: true
                            }
                        }
                    ]
                },
                {
                    test: /\.(png|svg|jpg|gif|webp)$/,
                    use: [
                        'file-loader'
                    ]
                }
            ]
        },
        plugins: [
            htmlPlugin,
            new webpack.DefinePlugin(envKeys)
        ]
    }
};