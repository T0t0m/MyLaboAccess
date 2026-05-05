import 'package:flutter_dotenv/flutter_dotenv.dart';



final String apiBaseUrl = dotenv.env['API_URL'] ?? 'http://127.0.0.1/mylabo_db/api';