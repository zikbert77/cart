<?php

/**
 * Write your own functions and use it anywhere in your beautiful code.
*/

/**
 * Pretty variable output
 * @param mixed $var
 */
function dump($var)
{
    echo '<pre style="background-color: black; color: darkorange; padding: 5px;">';
    print_r($var);
    echo '</pre>';
}

/**
 * @param mixed $var
 *
 * Variable output and stop script
 */
function dd($var)
{
    echo '<pre style="background-color: black; color: whitesmoke; padding: 5px;">';
    print_r($var);
    echo '</pre>';
    exit();
}

/**
 * @param string $routeName
 * @param array $optionArray
 *
 * @return string
 *
 * Return formatting path with $optionArray parameters if it defined
*/
function path($routeName, $optionArray = []) : string
{
    try {
        $routes = include(ROOT . '/app/config/routes.php');

        if (array_key_exists($routeName, $routes))
            $path = $routes[$routeName]['path'];
        else{
            components\Logger::log(sprintf('Route %s not exist', $routeName));
            throw new Exception(sprintf('Route %s not exist', $routeName));
        }

        if (!empty($optionArray)){
            $replacement = '';
            foreach ($optionArray as $item) {
                $replacement .= '/' . $item;
            }

            $explode = explode('/([', $path);

            return $explode[0] . $replacement;
        }

        return $path;
    } catch (Exception $e){
        components\Logger::log($e->getMessage());
        return die($e->getMessage());
    }
}

/**
 * @param $url string
 * @return mixed
 */
function redirect($url){
    try {
        header("Location: $url");
        return true;
    } catch (Exception $e) {
        components\Logger::log($e->getMessage());
        return die($e->getMessage());
    }
}

/**
 * @param string $path
 * @param array $optionsArray
 * @return mixed
 */
function redirectToRoute($path, $optionsArray = [])
{
    try {
        $newpath = path($path, $optionsArray);

        header("Location: $newpath");
        return true;
    } catch (Exception $e) {
        components\Logger::log($e->getMessage());
        return die($e->getMessage());
    }
}