/**
 * //=={Table}==//.php
 * Date: //=={date}==//
 */

namespace //=={namespace}==//;

use app\common\BasePage;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
//=={use}==//


/**
 * //=={describe}==//
 * Class //=={Table}==//Page
 * @package //=={namespace}==//\//=={Table}==//Page
 */
class //=={Table}==//Page extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return PageData
     * @throws \app\common\SdException
     */
    public function listPageData(): PageData
    {
        $table = PageData::create([
            //=={table_page}==//
        ]);

        // 更多处理事件及其他设置，$table->setHandleAttr() 可设置操作栏的属性

        return $table;
    }

    /**
    * 生成表单的数据
    * @param string $scene
    * @param array $default_data
    * @return Form
    */
    public function formPageData(string $scene, array $default_data = []): Form
    {
        $unit = [
            //=={form_data}==//
        ];

        $form = Form::create($unit, $default_data)->setScene($scene);

        return $form;
    }


//=={search_form}==//

}
