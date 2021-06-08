/**
 * //=={Table}==//.php
 * Date: //=={date}==//
 * User: chenlong <vip_chenlong@163.com>
 */

namespace //=={namespace}==//;

use app\common\BasePage;
use sdModule\layui\tablePage\ListsPage;
use sdModule\layui\tablePage\module\TableAux;
use sdModule\layui\form\Form;
//=={use}==//


/**
 * Class //=={Table}==//
 * @package //=={namespace}==//\//=={Table}==//
 */
class //=={Table}==// extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return ListsPage
     */
    public function getTablePageData(): ListsPage
    {
        $table = ListsPage::create([
            //=={table_page}==//
        ]);

        $table->setHandleAttr([
            'align' => 'center',
            'width' => 150
        ]);
        return $table;
    }

    /**
    * 生成表单的数据
    * @param string $scene
    * @param array $default_data
    * @return Form
    * @throws \ReflectionException
    * @throws \app\common\SdException
    */
    public function formData(string $scene, array $default_data = []): Form
    {
        $unit = [
            //=={form_data}==//
        ];

        $form = Form::create($unit, $scene)->setDefaultData($default_data);

        return $form->complete();
    }


    /**
     * 创建搜索表单的数据
     * @return Form
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function searchFormData(): Form
    {
        $form_data = [//=={search_form}==//];
        return Form::create($form_data)->setSubmitHtml()->complete();
    }

}
