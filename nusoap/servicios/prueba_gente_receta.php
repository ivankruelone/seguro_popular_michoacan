<?php
require_once('../lib/crud.php'); 

$xml = "<?xml version='1.0'?><receta suc='18701' id='1' folio='097339000054' fecha='2013-01-31 23:08:00' enviada='0' corte='0'><producto><id>43</id><producto_id>3</producto_id><piezas>1</piezas><precio>10</precio></producto><producto><id>44</id><producto_id>1</producto_id><piezas>1</piezas><precio>10</precio></producto><producto><id>45</id><producto_id>2</producto_id><piezas>1</piezas><precio>10</precio></producto></receta>";
$xml2 = "<?xml version='1.0'?><corte suc='18701' id='10' fondo='0' retiro='0' ventas='330' total='330' dinero='330' faltante='0' sobrante='0' fecha='2013-03-06 13:27:01' usuario_id='1'><r><id>54</id></r><r><id>55</id></r><r><id>56</id></r></corte>";
$xml3 = "<?xml version='1.0' ?><inventario suc='18701' id='13' fecha='2013-03-11 09:11:13' usuario_id='1'><d><id>1</id><can>1000</can></d><d><id>2</id><can>500</can></d><d><id>3</id><can>150</can></d><d><id>4</id><can>0</can></d><d><id>5</id><can>0</can></d><d><id>6</id><can>0</can></d><d><id>7</id><can>0</can></d><d><id>8</id><can>0</can></d><d><id>9</id><can>0</can></d><d><id>10</id><can>0</can></d><d><id>11</id><can>0</can></d><d><id>12</id><can>0</can></d><d><id>13</id><can>0</can></d><d><id>14</id><can>0</can></d><d><id>15</id><can>0</can></d><d><id>16</id><can>0</can></d><d><id>17</id><can>0</can></d><d><id>18</id><can>0</can></d><d><id>19</id><can>0</can></d><d><id>20</id><can>0</can></d><d><id>21</id><can>0</can></d><d><id>22</id><can>0</can></d><d><id>23</id><can>0</can></d><d><id>24</id><can>0</can></d><d><id>25</id><can>0</can></d><d><id>26</id><can>0</can></d><d><id>27</id><can>0</can></d><d><id>28</id><can>0</can></d><d><id>29</id><can>0</can></d><d><id>30</id><can>0</can></d><d><id>31</id><can>0</can></d><d><id>32</id><can>0</can></d><d><id>33</id><can>0</can></d><d><id>34</id><can>0</can></d><d><id>35</id><can>0</can></d><d><id>36</id><can>0</can></d><d><id>37</id><can>0</can></d><d><id>38</id><can>0</can></d><d><id>39</id><can>0</can></d><d><id>40</id><can>0</can></d><d><id>41</id><can>0</can></d><d><id>42</id><can>0</can></d><d><id>43</id><can>0</can></d><d><id>44</id><can>0</can></d><d><id>45</id><can>0</can></d><d><id>46</id><can>0</can></d><d><id>47</id><can>0</can></d><d><id>48</id><can>0</can></d><d><id>49</id><can>0</can></d><d><id>50</id><can>0</can></d><d><id>51</id><can>0</can></d><d><id>52</id><can>0</can></d><d><id>53</id><can>0</can></d><d><id>54</id><can>0</can></d><d><id>55</id><can>0</can></d><d><id>56</id><can>0</can></d><d><id>57</id><can>0</can></d><d><id>58</id><can>0</can></d><d><id>59</id><can>0</can></d><d><id>60</id><can>0</can></d><d><id>61</id><can>0</can></d><d><id>62</id><can>0</can></d><d><id>63</id><can>0</can></d><d><id>64</id><can>0</can></d><d><id>65</id><can>0</can></d><d><id>66</id><can>0</can></d><d><id>67</id><can>0</can></d><d><id>68</id><can>0</can></d><d><id>69</id><can>0</can></d><d><id>70</id><can>0</can></d><d><id>71</id><can>0</can></d><d><id>72</id><can>0</can></d><d><id>73</id><can>0</can></d><d><id>74</id><can>0</can></d><d><id>75</id><can>0</can></d><d><id>76</id><can>0</can></d><d><id>77</id><can>0</can></d><d><id>78</id><can>0</can></d><d><id>79</id><can>0</can></d><d><id>80</id><can>0</can></d><d><id>81</id><can>0</can></d><d><id>82</id><can>0</can></d><d><id>83</id><can>0</can></d><d><id>84</id><can>0</can></d><d><id>85</id><can>0</can></d><d><id>86</id><can>0</can></d><d><id>87</id><can>0</can></d><d><id>88</id><can>0</can></d><d><id>89</id><can>0</can></d><d><id>90</id><can>0</can></d><d><id>91</id><can>0</can></d><d><id>92</id><can>0</can></d><d><id>93</id><can>0</can></d><d><id>94</id><can>0</can></d><d><id>95</id><can>0</can></d><d><id>96</id><can>0</can></d><d><id>97</id><can>0</can></d><d><id>98</id><can>0</can></d><d><id>99</id><can>0</can></d><d><id>100</id><can>0</can></d><d><id>101</id><can>0</can></d><d><id>102</id><can>0</can></d><d><id>103</id><can>0</can></d><d><id>104</id><can>0</can></d><d><id>105</id><can>0</can></d><d><id>106</id><can>0</can></d><d><id>107</id><can>0</can></d><d><id>108</id><can>0</can></d><d><id>109</id><can>0</can></d><d><id>110</id><can>0</can></d><d><id>111</id><can>0</can></d><d><id>112</id><can>0</can></d><d><id>113</id><can>0</can></d><d><id>114</id><can>0</can></d><d><id>115</id><can>0</can></d><d><id>116</id><can>0</can></d><d><id>117</id><can>0</can></d><d><id>118</id><can>0</can></d><d><id>119</id><can>0</can></d><d><id>120</id><can>0</can></d><d><id>121</id><can>0</can></d><d><id>122</id><can>0</can></d><d><id>123</id><can>0</can></d><h><id>1</id><can>150</can></h><h><id>2</id><can>750</can></h><h><id>3</id><can>450</can></h><h><id>4</id><can>0</can></h><h><id>5</id><can>0</can></h><h><id>6</id><can>0</can></h><h><id>7</id><can>0</can></h><h><id>8</id><can>0</can></h><h><id>9</id><can>0</can></h><h><id>10</id><can>0</can></h><h><id>11</id><can>0</can></h><h><id>12</id><can>0</can></h><h><id>13</id><can>0</can></h><h><id>14</id><can>0</can></h><h><id>15</id><can>0</can></h><h><id>16</id><can>0</can></h><h><id>17</id><can>0</can></h><h><id>18</id><can>0</can></h><h><id>19</id><can>0</can></h><h><id>20</id><can>0</can></h><h><id>21</id><can>0</can></h><h><id>22</id><can>0</can></h><h><id>23</id><can>0</can></h><h><id>24</id><can>0</can></h><h><id>25</id><can>0</can></h><h><id>26</id><can>0</can></h><h><id>27</id><can>0</can></h><h><id>28</id><can>0</can></h><h><id>29</id><can>0</can></h><h><id>30</id><can>0</can></h><h><id>31</id><can>0</can></h><h><id>32</id><can>0</can></h><h><id>33</id><can>0</can></h><h><id>34</id><can>0</can></h><h><id>35</id><can>0</can></h><h><id>36</id><can>0</can></h><h><id>37</id><can>0</can></h><h><id>38</id><can>0</can></h><h><id>39</id><can>0</can></h><h><id>40</id><can>0</can></h><h><id>41</id><can>0</can></h><h><id>42</id><can>0</can></h><h><id>43</id><can>0</can></h><h><id>44</id><can>0</can></h><h><id>45</id><can>0</can></h><h><id>46</id><can>0</can></h><h><id>47</id><can>0</can></h><h><id>48</id><can>0</can></h><h><id>49</id><can>0</can></h><h><id>50</id><can>0</can></h><h><id>51</id><can>0</can></h><h><id>52</id><can>0</can></h><h><id>53</id><can>0</can></h><h><id>54</id><can>0</can></h><h><id>55</id><can>0</can></h><h><id>56</id><can>0</can></h><h><id>57</id><can>0</can></h><h><id>58</id><can>0</can></h><h><id>59</id><can>0</can></h><h><id>60</id><can>0</can></h><h><id>61</id><can>0</can></h><h><id>62</id><can>0</can></h><h><id>63</id><can>0</can></h><h><id>64</id><can>0</can></h><h><id>65</id><can>0</can></h><h><id>66</id><can>0</can></h><h><id>67</id><can>0</can></h><h><id>68</id><can>0</can></h><h><id>69</id><can>0</can></h><h><id>70</id><can>0</can></h><h><id>71</id><can>0</can></h><h><id>72</id><can>0</can></h><h><id>73</id><can>0</can></h><h><id>74</id><can>0</can></h><h><id>75</id><can>0</can></h><h><id>76</id><can>0</can></h><h><id>77</id><can>0</can></h><h><id>78</id><can>0</can></h><h><id>79</id><can>0</can></h><h><id>80</id><can>0</can></h><h><id>81</id><can>0</can></h><h><id>82</id><can>0</can></h><h><id>83</id><can>0</can></h><h><id>84</id><can>0</can></h><h><id>85</id><can>0</can></h><h><id>86</id><can>0</can></h><h><id>87</id><can>0</can></h><h><id>88</id><can>0</can></h><h><id>89</id><can>0</can></h><h><id>90</id><can>0</can></h><h><id>91</id><can>0</can></h><h><id>92</id><can>0</can></h><h><id>93</id><can>0</can></h><h><id>94</id><can>0</can></h><h><id>95</id><can>0</can></h><h><id>96</id><can>0</can></h><h><id>97</id><can>0</can></h><h><id>98</id><can>0</can></h><h><id>99</id><can>0</can></h><h><id>100</id><can>0</can></h><h><id>101</id><can>0</can></h><h><id>102</id><can>0</can></h><h><id>103</id><can>0</can></h><h><id>104</id><can>0</can></h><h><id>105</id><can>0</can></h><h><id>106</id><can>0</can></h><h><id>107</id><can>0</can></h><h><id>108</id><can>0</can></h><h><id>109</id><can>0</can></h><h><id>110</id><can>0</can></h><h><id>111</id><can>0</can></h><h><id>112</id><can>0</can></h><h><id>113</id><can>0</can></h><h><id>114</id><can>0</can></h><h><id>115</id><can>0</can></h><h><id>116</id><can>0</can></h><h><id>117</id><can>0</can></h><h><id>118</id><can>0</can></h><h><id>119</id><can>0</can></h><h><id>120</id><can>0</can></h><h><id>121</id><can>0</can></h><h><id>122</id><can>0</can></h><h><id>123</id><can>0</can></h></inventario>
";
    require_once('../lib/crud.php'); 

    $xmlDoc = simplexml_load_string($xml3);
    
    echo "<pre>";
    //print_r($xmlDoc);
    echo "</pre>";
    
//    die();
    $a = array(
        (integer)$xmlDoc->attributes()->suc,
        (integer)$xmlDoc->attributes()->id,
        (string)$xmlDoc->attributes()->fecha,
        (integer)$xmlDoc->attributes()->usuario_id
        );
    
    $db = new Database();
    $db->connect();
    $db->insert('gente_inv_c', $a, "sucursal, inv_id, fecha, usuario_id");  
    $res = $db->getResult();  
    
    if($res > 0){
    
        foreach($xmlDoc->d as $d)
        {
         //sucursal, producto_id, inv, id, ultima   
            $b = array(
                (integer)$xmlDoc->attributes()->suc,
                (integer)$d->id,
                (integer)$d->can,
                date('Y-m-d H:i:s')
                );
         //sucursal, producto_id, inv, c_id, id
            $c = array(
                (integer)$xmlDoc->attributes()->suc,
                (integer)$d->id,
                (integer)$d->can,
                (integer)$xmlDoc->attributes()->id
                );
            
            $db->insert('gente_inventario', $b, "sucursal, producto_id, inv, ultima", "inv = values(inv), ultima = values(ultima)");  
            $db->insert('gente_inventario_d', $c, "sucursal, producto_id, inv, c_id", "inv = values(inv)");  
            
        }
        
        foreach($xmlDoc->h as $h)
        {
         //sucursal, producto_id, inv, c_id, id
            $d = array(
                (integer)$xmlDoc->attributes()->suc,
                (integer)$h->id,
                (integer)$h->can,
                (integer)$xmlDoc->attributes()->id
                );
            
            $db->insert('gente_inventario_h', $d, "sucursal, producto_id, inv, c_id", "inv = values(inv)");  
            
        }

        echo $res;
    }else{
        echo 0;
    }

?>