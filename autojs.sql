-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jun 06, 2023 at 04:41 AM
-- Server version: 8.0.32
-- PHP Version: 8.1.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `autojs`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_role_id` bigint UNSIGNED NOT NULL COMMENT '管理员角色ID',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `mobile` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `status` tinyint UNSIGNED NOT NULL COMMENT '状态（1正常，2禁用）',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_authenticator` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Google身份验证器',
  `create_time` int UNSIGNED NOT NULL COMMENT '创建时间',
  `update_time` int UNSIGNED NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `admin_role_id`, `name`, `mobile`, `username`, `password`, `status`, `remember_token`, `google_authenticator`, `create_time`, `update_time`) VALUES
(76, 1, '超级管理员', '', 'boss', '$2y$10$fG8E7XLJL52PKoJlWsS13u2C9Nmi/ZT3LVt9F.yVr0ZiOeT/F58Qi', 1, 'uQxJMy79oEpuTAdaJYM8z9Qwg5vuJghLHg5GEXMEuP8Nd84idClNBP6lmcmn', 'DHRYTGEGZSLXTERM', 1676972050, 1681405339)

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_records`
--

CREATE TABLE `admin_login_records` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED NOT NULL COMMENT '管理员ID',
  `login_ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录IP',
  `login_time` int UNSIGNED NOT NULL COMMENT '登录时间',
  `create_time` int UNSIGNED NOT NULL COMMENT '创建时间',
  `update_time` int UNSIGNED NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `admin_permissions`
--

CREATE TABLE `admin_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `pid` bigint UNSIGNED NOT NULL COMMENT '父级ID',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `uri` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URI（模块.控制器.操作）',
  `sort` int UNSIGNED NOT NULL COMMENT '排序值',
  `status` tinyint UNSIGNED NOT NULL COMMENT '状态（1正常，2禁用）',
  `create_time` int UNSIGNED NOT NULL COMMENT '创建时间',
  `update_time` int UNSIGNED NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_permissions`
--

INSERT INTO `admin_permissions` (`id`, `pid`, `name`, `uri`, `sort`, `status`, `create_time`, `update_time`) VALUES
(1, 0, '面板', '', 1, 1, 1609390911, 1684471146),
(2, 1, '面板', 'admin.homepage.console', 0, 1, 1609390911, 1684471120),
(3, 0, '权限管理', '', 4294967295, 1, 1609390911, 1684197203),
(4, 3, '权限列表', 'admin.adminpermission.index', 0, 1, 1609390911, 1609390911),
(5, 4, '添加', 'admin.adminpermission.create', 0, 1, 1609390911, 1609390911),
(6, 4, '编辑', 'admin.adminpermission.edit', 0, 1, 1609390911, 1609390911),
(7, 3, '角色列表', 'admin.adminrole.index', 0, 1, 1609390911, 1609390911),
(8, 7, '添加', 'admin.adminrole.create', 0, 1, 1609390911, 1609390911),
(9, 7, '编辑', 'admin.adminrole.edit', 0, 1, 1609390911, 1609390911),
(10, 7, '权限', 'admin.adminrole.permission', 0, 1, 1609390911, 1609390911),
(11, 3, '管理列表', 'admin.admin.index', 0, 1, 1609390911, 1609390911),
(12, 11, '添加', 'admin.admin.create', 0, 1, 1609390911, 1609390911),
(13, 11, '编辑', 'admin.admin.edit', 0, 1, 1609390911, 1609390911),
(14, 3, '登录记录', 'admin.adminloginrecord.index', 0, 1, 1609390911, 1609390911),
(15, 0, '上下分', '', 1, 1, 1609390911, 1684197072),
(16, 292, '代理管理', 'admin.agent.index', 1, 1, 1609390911, 1684089110),
(17, 16, '添加', 'admin.agent.create', 0, 1, 1609390911, 1609390911),
(18, 16, '编辑', 'admin.agent.edit', 0, 1, 1609390911, 1609390911),
(19, 16, '重置密码', 'admin.agent.password', 0, 1, 1609390911, 1609390911),
(20, 292, '商户管理', 'admin.merchant.index', 0, 1, 1609390911, 1684089085),
(21, 20, '添加', 'admin.merchant.create', 0, 1, 1609390911, 1609390911),
(22, 20, '编辑', 'admin.merchant.edit', 0, 1, 1609390911, 1609390911),
(23, 20, '重置密码', 'admin.merchant.password', 0, 1, 1609390911, 1609390911),
(24, 20, '重置密钥', 'admin.merchant.secretkey', 0, 1, 1609390911, 1609390911),
(39, 275, '系统配置', 'admin.system.config', 3, 1, 1609390911, 1684197003),
(42, 0, '数据统计', '', 5, 1, 1609390911, 1683735703),
(46, 32, '导出', 'admin.payinorder.export', 0, 1, 1609390911, 1609390911),
(47, 34, '导出', 'admin.payoutorder.export', 0, 1, 1609390911, 1609390911),
(48, 11, '谷歌身份验证器', 'admin.admin.googleauthenticator', 0, 1, 1609390911, 1609390911),
(49, 16, '谷歌身份验证器', 'admin.agent.googleauthenticator', 0, 1, 1609390911, 1609390911),
(50, 20, '谷歌身份验证器', 'admin.merchant.googleauthenticator', 0, 1, 1609390911, 1609390911),
(51, 32, '通知', 'admin.payinorder.notify', 0, 1, 1609390911, 1609390911),
(52, 34, '通知', 'admin.payoutorder.notify', 0, 1, 1609390911, 1609390911),
(55, 0, '日志管理', '', 22, 1, 1609678584, 1683735682),
(57, 16, '快速登录', 'admin.agent.quicklogin', 0, 1, 1609851120, 1609851169),
(58, 20, '快速登录', 'admin.merchant.quicklogin', 0, 1, 1609851218, 1609851218),
(64, 292, '商户资金变动', 'admin.log.merchantaccountlog', 0, 1, 1610695059, 1684089064),
(93, 15, '上分', 'admin.autouppoint.index', 22, 1, 1614155873, 1684254942),
(94, 93, '审核', 'admin.autouppoint.audit', 0, 1, 1614155923, 1614155923),
(95, 201, '审核换汇', 'admin.autodownpoint.index', 0, 1, 1614243647, 1629444789),
(96, 95, '审核', 'admin.autodownpoint.audit', 0, 1, 1614243672, 1614243672),
(219, 275, '后台访问日志', 'admin.weblog.index', 1, 1, 1630661194, 1684197108),
(220, 219, '详情', 'admin.weblog.detail', 1, 1, 1630661226, 1630661226),
(221, 147, '日志管理', 'admin.channelorder.log', 1, 1, 1630668424, 1630668424),
(275, 0, '软件&系统', '', 1111111, 1, 1681669327, 1684197035),
(276, 275, '进程配置', 'admin.devtool.index', 1, 1, 1681669401, 1681669401),
(312, 275, 'phpinfo', 'admin.devtool.phpinfo', 1, 1, 1684977796, 1684977796),
(313, 275, '系统状态', 'admin.devtool.probe', 1, 1, 1684977848, 1684977848);

-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

CREATE TABLE `admin_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `identity` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标识',
  `status` tinyint UNSIGNED NOT NULL COMMENT '状态（1正常，2禁用）',
  `permission` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限（多个设置，用|隔开）',
  `create_time` int UNSIGNED NOT NULL COMMENT '创建时间',
  `update_time` int UNSIGNED NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`, `identity`, `status`, `permission`, `create_time`, `update_time`) VALUES
(1, '超级管理员', 'superman', 1, '1|2|261|15|293|294|93|94|158|31|32|33|46|51|34|38|47|52|103|133|135|297|286|287|281|291|124|125|126|273|274|292|20|21|22|23|24|36|50|58|64|16|17|18|19|49|57|308|309|310|311|277|288|289|290|285|284|255|25|26|27|137|296|28|29|30|37|136|165|257|258|259|298|300|301|302|303|304|305|306|42|55|275|219|220|276|312|313|282|39|3|4|5|6|7|8|9|10|11|12|13|48|14', 1609390911, 1685849249),

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` bigint UNSIGNED NOT NULL,
  `agentid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '代理号',
  `pid` bigint UNSIGNED NOT NULL COMMENT '父级ID',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '代理名称',
  `payin_poundage` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '代收手续费（百分之几+每笔）',
  `payout_poundage` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '代付手续费（百分之几+每笔）',
  `status` tinyint UNSIGNED NOT NULL COMMENT '状态（1正常，2禁用）',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_authenticator` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Google身份验证器',
  `create_time` int UNSIGNED NOT NULL COMMENT '创建时间',
  `update_time` int UNSIGNED NOT NULL COMMENT '更新时间',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_id`
--

CREATE TABLE `auto_id` (
  `id` int NOT NULL,
  `t` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `merchants`
--

CREATE TABLE `merchants` (
  `id` bigint UNSIGNED NOT NULL,
  `merchantid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商户号',
  `secretkey` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密钥',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `balance` decimal(11,2) NOT NULL COMMENT '余额',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_id` bigint UNSIGNED NOT NULL DEFAULT '0' COMMENT '代理ID',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商户名称',
  `identity` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '统一引用',
  `settlement_cycle` int UNSIGNED NOT NULL COMMENT '结算周期（D+几）',
  `status` tinyint UNSIGNED NOT NULL COMMENT '状态（1正常，2禁用）',
  `manual_payin_poundage` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手动代收手续费（百分之几+每笔）',
  `payin_poundage` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '代收手续费（百分之几+每笔）',
  `payin_limit` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '代收限额',
  `payin_status` tinyint UNSIGNED NOT NULL COMMENT '代收状态（1正常，2禁用）',
  `payin_is_cycle` tinyint UNSIGNED NOT NULL COMMENT '代收是否轮训（1是，2否）',
  `payin_channel_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '代收通道ID',
  `payout_poundage` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '代付手续费（百分之几+每笔）',
  `payout_trigger` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '代付：代付金额多少后不收每笔手续费',
  `payout_limit` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '代付限额',
  `payout_status` tinyint UNSIGNED NOT NULL COMMENT '代付状态（1正常，2禁用）',
  `payout_is_cycle` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '代付类型（1正常，2轮训，3排序）',
  `payout_channel_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '代付通道ID',
  `is_delete` tinyint UNSIGNED NOT NULL COMMENT '是否删除（1是，2否）',
  `google_authenticator` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Google身份验证器',
  `request_ip` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '报备IP',
  `request_domain` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '报备域名',
  `create_time` int UNSIGNED NOT NULL COMMENT '创建时间',
  `update_time` int UNSIGNED NOT NULL COMMENT '更新时间',
  `jump_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商户跳转链接',
  `payinsettlement_proportion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '代收结算比例（百分之几）',
  `payinsettlement_remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '下发税务成本备注',
  `is_settlement` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否结算（1是，2否）',
  `type` tinyint NOT NULL DEFAULT '0' COMMENT '商户业务类型',
  `url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商户官网',
  `in_sorting_channel_group` int NOT NULL DEFAULT '0',
  `out_sorting_channel_group` int NOT NULL DEFAULT '0',
  `credits_amount` decimal(15,2) DEFAULT '0.00' COMMENT '用户信用额度',
  `freeze_amount` decimal(15,2) DEFAULT '0.00' COMMENT '用户冻结金额',
  `in_min_amount` decimal(15,2) DEFAULT '10.00' COMMENT '代收最低金额',
  `in_max_amount` decimal(15,2) DEFAULT '20000.00' COMMENT '代收最高金额',
  `out_min_amount` decimal(15,2) DEFAULT '100.00' COMMENT '代付最低金额',
  `out_max_amount` decimal(15,2) DEFAULT '20000.00' COMMENT '代付最高金额',
  `agent_in_fee` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '代理的代收费率',
  `agent_out_fee` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '代理的代付费率',
  `allow_manual_payout` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许手动代付：1-是;2-否',
  `product_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '产品名称',
  `product_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '产品logo',
  `tg_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tg群ID',
  `exchange_min_amount` decimal(15,2) NOT NULL DEFAULT '150000.00' COMMENT '换汇最低金额',
  `tg_contact` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'tg联系人',
  `is_show_video` tinyint(1) NOT NULL DEFAULT '1' COMMENT '充值页面视频是否显示：1-是；2-否'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `merchants`
--

INSERT INTO `merchants` (`id`, `merchantid`, `secretkey`, `password`, `balance`, `remember_token`, `agent_id`, `name`, `identity`, `settlement_cycle`, `status`, `manual_payin_poundage`, `payin_poundage`, `payin_limit`, `payin_status`, `payin_is_cycle`, `payin_channel_id`, `payout_poundage`, `payout_trigger`, `payout_limit`, `payout_status`, `payout_is_cycle`, `payout_channel_id`, `is_delete`, `google_authenticator`, `request_ip`, `request_domain`, `create_time`, `update_time`, `jump_link`, `payinsettlement_proportion`, `payinsettlement_remark`, `is_settlement`, `type`, `url`, `in_sorting_channel_group`, `out_sorting_channel_group`, `credits_amount`, `freeze_amount`, `in_min_amount`, `in_max_amount`, `out_min_amount`, `out_max_amount`, `agent_in_fee`, `agent_out_fee`, `allow_manual_payout`, `product_name`, `product_logo`, `tg_id`, `exchange_min_amount`, `tg_contact`, `is_show_video`) VALUES
(500, 'TEST001', 'fe658d280e3e127a5103f62360393ba2', '$2y$10$tH6fJK/o/HTnV7DUVBzdXummKxCbriXflX7bHgIY0kAFBuNReNoFC', 0.00, '7m42xKCInYoZOAwEGbUPkiB2grk2qPhnrBUs6Pxq2ngYLLQ0qO9U9HnuaK80', 0, '测试商户', 'intest', 0, 1, '6.5+0', '6.5+0', '100000000.00', 1, 1, '766', '4+8', 0.00, '100000000.00', 1, 1, '291', 2, 'NMDYFHUDH2GPKDT5', '111111', '暂无报备域名', 1676973107, 1684364195, '', '100.00', '暂无下发备注', 1, 10, '暂无商户官网', 0, 0, 0.00, 0.00, 10.00, 200000.00, 100.00, 50000.00, '0+0', '0+0', 2, '', '', '1', 150000.00, '11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级ID',
  `invite_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邀请码',
  `package` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '包',
  `head_portrait` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮箱',
  `email_verify_status` tinyint NOT NULL COMMENT '邮箱验证状态（-1未验证，1已验证）',
  `status` tinyint NOT NULL COMMENT '状态（-1禁用，1启用）',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '备注',
  `register_time` int UNSIGNED NOT NULL COMMENT '注册时间',
  `last_active_time` int UNSIGNED NOT NULL COMMENT '最后活跃时间',
  `last_active_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '最后活跃IP',
  `unread` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '未读',
  `update_time` int UNSIGNED NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `web_log`
--

CREATE TABLE `web_log` (
  `id` int NOT NULL,
  `model` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '模块',
  `controll` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '控制器',
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '方法',
  `request_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '请求方式',
  `params` json DEFAULT NULL COMMENT '参数',
  `admin_user_id` int DEFAULT NULL COMMENT '操作用户ID',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  `request_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_login_records`
--
ALTER TABLE `admin_login_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auto_id`
--
ALTER TABLE `auto_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchants`
--
ALTER TABLE `merchants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `web_log`
--
ALTER TABLE `web_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `admin_login_records`
--
ALTER TABLE `admin_login_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3508;

--
-- AUTO_INCREMENT for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=314;

--
-- AUTO_INCREMENT for table `admin_roles`
--
ALTER TABLE `admin_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auto_id`
--
ALTER TABLE `auto_id`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `merchants`
--
ALTER TABLE `merchants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=501;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `web_log`
--
ALTER TABLE `web_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
