<?php

declare(strict_types=1);

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * Copyright (c) 2014-2019 Yuuki Takezawa
 *
 */

namespace Ytake\LaravelSmarty\Engines;

use Illuminate\Contracts\View\Engine as EngineInterface;
use Throwable;
use Ytake\LaravelSmarty\Smarty;

use function extract;

use const EXTR_SKIP;

/**
 * Class SmartyEngine
 *
 * @author  yuuki.takezawa <yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyEngine implements EngineInterface
{
    /** @var Smarty $smarty */
    protected $smarty;

    /**
     * @param Smarty $smarty
     */
    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    /**
     * {@inheritdoc}
     * @throws Throwable
     */
    public function get($path, array $data = [])
    {
        return $this->evaluatePath($path, $data);
    }

    /**
     * Get the evaluated contents of the view at the given path.
     *
     * @param string $path
     * @param array $data
     *
     * @return string
     * @throws Throwable
     */
    protected function evaluatePath(string $path, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        if (!$this->smarty->isCached($path)) {
            foreach ($data as $var => $val) {
                $this->smarty->assign($var, $val);
            }
        }
        // render
        $cacheId = $data['smarty.cache_id'] ?? null;
        $compileId = $data['smarty.compile_id'] ?? null;
        return $this->smarty->fetch($path, $cacheId, $compileId);
    }
}
