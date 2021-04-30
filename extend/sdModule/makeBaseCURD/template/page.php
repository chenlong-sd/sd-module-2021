/**
 * //=={Table}==//.php
 * Date: //=={date}==//
 * User: chenlong <vip_chenlong@163.com>
 */

namespace //=={namespace}==//;

use app\common\BasePage;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
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
     * @return TablePage
     */
    public function getTablePageData(): TablePage
    {
        $table = TablePage::create([
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
     * 列表页面的名字
     * @return string
     */
    public function listPageName(): string
    {
        return "//=={page_name}==//";
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
        return Form::create($form_data)->setNoSubmit()->complete();
    }

}
