<?php

namespace App\Utility;

use Cake\Core\Configure;

/**
 * Class GitHubApiClient
 * Utility class to acces GitHub API from within this app.
 *
 * @package App\Utility
 */
class GitHubApiClient
{
    const STATUS_ADDED = 'added';
    const STATUS_MODIFIED = 'modified';
    const STATUS_REMOVED = 'removed';

    /**
     * Executes a GET request on the desired URL.
     *
     * @param string $url The URL to request
     * @param bool $parseJson Whether the result should be parsed in JSON
     * @return array|null|string The request result
     */
    private static function executeGetRequest(string $url, $parseJson = true)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if (Configure::read('App.repositoryCredentials') !== false) {
            curl_setopt($ch, CURLOPT_USERPWD, Configure::read('App.repositoryCredentials'));
        }

        // need to set user agent due too GitHubs API restrictions
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: ' . Configure::read('App.name')
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        if ($parseJson) {
            return json_decode($result, true);
        } else {
            return $result;
        }
    }

    /**
     * Returns a list of all tags in the applications repository.
     *
     * @return array|null The available tags
     */
    public static function getTags()
    {
        $tagsUrl = Configure::read('App.repository') . '/tags';

        return static::executeGetRequest($tagsUrl);
    }

    /**
     * Returns information about the latest changes between the latest and the current
     * application version.
     *
     * @param string $currentVersion The current application version
     * @return array|null The latest changes information
     */
    public static function getLatestChanges(string $currentVersion)
    {
        $tagList = static::getTags();
        if (isset($tagList[0])) {
            $latestChangesUrl = Configure::read('App.repository') . '/compare/' . $currentVersion . '...' . $tagList[0]['name'];

            return static::executeGetRequest($latestChangesUrl);
        } else {
            return null;
        }
    }

    /**
     * Returns the content of a file specified by its raw URL.
     *
     * @param string $rawUrl The raw URL of the file
     * @return string|null The file content
     */
    public static function getFileContent(string $rawUrl)
    {
        return static::executeGetRequest($rawUrl, false);
    }
}