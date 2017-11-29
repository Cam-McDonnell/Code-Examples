from google.appengine.ext import ndb
import webapp2
import json

from google.appengine.ext import db
import os 
from google.appengine.ext.webapp import template 
import base64
import uuid
import json
from webapp2_extras import sessions
import urllib
from google.appengine.api import urlfetch


class Factory(ndb.Model):
	id = ndb.StringProperty()
	name = ndb.StringProperty(required=True)
	location = ndb.StringProperty()
	
class Textile(ndb.Model):
	id = ndb.StringProperty()
	name = ndb.StringProperty(required=True)
	content = ndb.StringProperty()
	factoryid = ndb.StringProperty()
	price = ndb.StringProperty()

class User(ndb.Model):
	id = ndb.StringProperty()
	name = ndb.StringProperty(required=True)
	googleid = ndb.StringProperty()	
	admin = ndb.BooleanProperty()
		
		
class FacHandler(webapp2.RequestHandler):
	def post(self):
		#if self.session.get('admin') == True:
		if self.request.body:
			fac_data = json.loads(self.request.body)
			new_fac = Factory(name=fac_data['name'], location=fac_data['location'])
			new_fac.put()
			key = new_fac.key.urlsafe()
			new_fac.id = key
			new_fac.put()
			fac_dict = new_fac.to_dict()
			fac_dict['self'] = '/fac/' + key
			self.response.write(json.dumps(fac_dict))
		else:
			self.response.set_status(400)	
		#else:
		#	self.response.set_status(401, message="401 not authorized")
			
	def get(self, id=None):				
		if id:
			fac = Factory.query().fetch()
			error = False
			count = 0			
			for x in fac:
				if x.id == id:			
					f = ndb.Key(urlsafe=id).get()				
					f_d = f.to_dict()
					f_d['self'] = "/fac/" + id
					self.response.write(json.dumps(f_d))
				else:
					count += 1
			if count == len(fac):
				error = True
			if error == True:		
				self.response.set_status(404, message="404 factory  not found")
				
		else:
			get_fac_query_results = [get_fac_query.to_dict()
			for get_fac_query in Factory.query()]
			self.response.write(json.dumps(get_fac_query_results))	
			
	
	
	def dispatch(self):
        # Get a session store for this request.
		self.session_store = sessions.get_store(request=self.request)

		try:
            # Dispatch the request.
			webapp2.RequestHandler.dispatch(self)
		finally:
            # Save all sessions.
			self.session_store.save_sessions(self.response)

	@webapp2.cached_property
	def session(self):
        # Returns a session using the default cookie key.
		return self.session_store.get_session()			

class TexHandler(webapp2.RequestHandler):
	def post(self):
		#if self.session.get('admin') == True:
		if self.request.body:
			tex_data = json.loads(self.request.body)
			new_tex = Textile(name=tex_data['name'],content=tex_data['content'], price=tex_data['price'],factoryid=tex_data['factoryid'])
			new_tex.put()
			key = new_tex.key.urlsafe()
			new_tex.id = key
			new_tex.put()
			tex_dict = new_tex.to_dict()
			tex_dict['self'] = '/tex/' + key
			self.response.write(json.dumps(tex_dict))
		else:
			self.response.set_status(400)	
		#else:
		#	self.response.set_status(401, message="401 not authorized")
			
	def get(self, id=None):				
		if self.session.get('admin') == True:
			if id:
				tex = Textile.query().fetch()
				error = False
				count = 0			
				for x in tex:
					if x.id == id:			
						t = ndb.Key(urlsafe=id).get()				
						t_d = t.to_dict()
						t_d['self'] = "/tex/" + id
						self.response.write(json.dumps(t_d))
					else:
						count += 1
				if count == len(tex):
					error = True
				if error == True:		
					self.response.set_status(404, message="404 textile  not found")
					
			else:
				get_tex_query_results = [get_tex_query.to_dict()
				for get_tex_query in Textile.query()]
				self.response.write(json.dumps(get_tex_query_results))					
		else:
			if id: 
				tex = Textile.query().fetch()
				error = False
				count = 0			
				for x in tex:
					if x.id == id:			
						t = ndb.Key(urlsafe=id).get()				
						t_d = t.to_dict()
						t_d['self'] = "/tex/" + id
						self.response.write(json.dumps(t_d['self']))
						self.response.write(json.dumps(t_d['id']))
						self.response.write(json.dumps(t_d['name']))
						self.response.write(json.dumps(t_d['content']))
						#self.response.write(json.dumps(t_d['factoryid']))
					else:
						count += 1
				if count == len(tex):
					error = True
				if error == True:		
					self.response.set_status(404, message="404 textile  not found")
			else:
				self.response.set_status(401, message="401 not authorized")
				
	def dispatch(self):
        # Get a session store for this request.
		self.session_store = sessions.get_store(request=self.request)

		try:
            # Dispatch the request.
			webapp2.RequestHandler.dispatch(self)
		finally:
            # Save all sessions.
			self.session_store.save_sessions(self.response)

	@webapp2.cached_property
	def session(self):
        # Returns a session using the default cookie key.
		return self.session_store.get_session()		
	
class UserHandler(webapp2.RequestHandler):
	def get(self, id=None):				
		if id:
			user = User.query().fetch()
			error = False
			count = 0			
			for x in user:
				if x.id == id:			
					t = ndb.Key(urlsafe=id).get()				
					t_d = t.to_dict()
					t_d['self'] = "/user/" + id
					self.response.write(json.dumps(t_d))
				else:
					count += 1
			if count == len(user):
				error = True
			if error == True:		
				self.response.set_status(404, message="404 user  not found")	
		
		else:
			get_user_query_results = [get_user_query.to_dict()
			for get_user_query in User.query()]	
			self.response.write(json.dumps(get_user_query_results))
			
class FacInventoryHandler(webapp2.RequestHandler):
	def get(self, id=None):
		if self.session.get('admin') == True:
			if id:
				fac = Factory.query().fetch()
				error = False
				count = 0			
				for x in fac:
					if x.id == id:			
						get_tex_query_results = [get_tex_query.to_dict()
						for get_tex_query in Textile.query(Textile.factoryid == id)]	
						self.response.write(json.dumps(get_tex_query_results))
						
						
					else:
						count += 1
				if count == len(fac):
					error = True
				if error == True:		
					self.response.set_status(404, message="404 factory  not found")
			else:		
				self.response.set_status(404, message="404 factoryid  not found")
		else:
			self.response.set_status(401, message="401 not authorized")
		
	def dispatch(self):
        # Get a session store for this request.
		self.session_store = sessions.get_store(request=self.request)

		try:
            # Dispatch the request.
			webapp2.RequestHandler.dispatch(self)
		finally:
            # Save all sessions.
			self.session_store.save_sessions(self.response)

	@webapp2.cached_property
	def session(self):
        # Returns a session using the default cookie key.
		return self.session_store.get_session()				

class UserDelHandler(webapp2.RequestHandler):
	def delete(self, id=None):
		if id:
			u = ndb.Key(urlsafe=id).get()								
			u.key.delete()
		else:
			self.response.set_status(400)

class FacDelHandler(webapp2.RequestHandler):
	def delete(self, id=None):
		if id:
			fac = ndb.Key(urlsafe=id).get()
			textile = None									
			tex = Textile.query().fetch()		
			for x in tex:
				if x.factoryid == id:
					textile = ndb.Key(urlsafe=x.id).get()
				else:
					self.response.set_status(400)
						
			textile.factoryid=None
			textile.put()
			fac.key.delete()
		else:
			self.response.set_status(400)

class TexDelHandler(webapp2.RequestHandler):
	def delete(self, id=None):
		if id:
			tex = ndb.Key(urlsafe=id).get()			
			tex.key.delete()
		else:
			self.response.set_status(400)			

		
class AdminHandler(webapp2.RequestHandler):
	def put(self, id=None):		
		if id:
			u = ndb.Key(urlsafe=id).get()
			user_data = json.loads(self.request.body)
			if user_data.get('admin'):
				u.admin = user_data['admin']
				u.put()
			else:
				self.response.set_status(400)
		else:
			self.response.set_status(400)

class TexModHandler(webapp2.RequestHandler):
	def put(self, id=None):
		if id:
			
			tex = ndb.Key(urlsafe=id).get()
			tex_data = json.loads(self.request.body)
			if tex_data.get('name'):
				tex.number = tex_data['name']
				tex.put()
			elif tex_data.get('price') and self.session.get('admin') == True:
				tex.current_boat = tex_data['price']
				tex.put()
			elif slip_data.get('factoryid'):
				tex.arrival_date = tex_data['factoryid']
				tex.put()
			elif tex_data.get('content'):
				tex.arrival_date = tex_data['content']
				tex.put()
			else:
				self.response.set_status(400)
		else:
			self.response.set_status(400)	

	def dispatch(self):
        # Get a session store for this request.
		self.session_store = sessions.get_store(request=self.request)

		try:
            # Dispatch the request.
			webapp2.RequestHandler.dispatch(self)
		finally:
            # Save all sessions.
			self.session_store.save_sessions(self.response)

	@webapp2.cached_property
	def session(self):
        # Returns a session using the default cookie key.
		return self.session_store.get_session()				
			
				
class OauthHandler(webapp2.RequestHandler):
	def get(self):
		code = json.dumps(self.request.GET.get('code'))
		state = json.dumps(self.request.GET.get('state'))

		statecheck = self.session.get('state')
		
		if state.replace('"', '') == statecheck:
			#url = 'https://www.googleapis.com/oauth2/v4/token'
			post_fields = {
				'code': code.replace('"', ''),
				'client_id': '733233126010-2ufd7rscja3ps1963h4u4itgc2ef2esm.apps.googleusercontent.com',
				'client_secret': 'Kcuzbb4dh7PtlL8AYymEjcE7',
				'redirect_uri': 'https://final-176617.appspot.com/oauth',
				'grant_type': 'authorization_code',				
			}
			headers = {'Content-Type': 'application/x-www-form-urlencoded'}
			try:
				request = urlfetch.fetch(
					url= "https://www.googleapis.com/oauth2/v4/token", 
					payload= urllib.urlencode(post_fields),
					method= urlfetch.POST,
					headers=headers
				)		
				#self.response.write(request.content)
				


				try: 
					result = json.loads(request.content)
					
					#self.response.write(result['access_token'])
					token = "Bearer " + result['access_token']
				
					headers = {'Authorization': token}
					request2 = urlfetch.fetch(
						url= "https://www.googleapis.com/plus/v1/people/me", 						
						method= urlfetch.GET,
						headers=headers
					)	
					#self.response.write(request2.content)
					

					#if account info is not in backend create account, otherwise login. 
					
					
					
					result2 = json.loads(request2.content)
					googleID = result2['id']
					fullName = result2['name']['givenName'] +' ' + result2['name']['familyName']
								
					user = User.query().fetch()
					count = 0			
					for x in user:
						if x.googleid == googleID:	

							#needs work handle login ->  save id and admin status to sessions
							
							
							self.session['id'] = x.id	
							self.session['admin'] = x.admin
							
							
							
							#self.response.write("Logged in!!")
							
							
							template_values = {
								'name' : fullName,
								'admin' : x.admin
							}
							path = os.path.join(os.path.dirname(__file__), 'www/oauth.html') 
							self.response.out.write(template.render(path, template_values))
							
						else:
							count += 1
					if count == len(user):
									
					
						new_user = User(name=fullName, googleid=googleID, admin=False)
						new_user.put()
						key = new_user.key.urlsafe()
						new_user.id = key
						new_user.put()
						user_dict = new_user.to_dict()
						user_dict['self'] = '/user/' + new_user.key.urlsafe()
						self.response.write("New User Created <br>")
						self.response.write(json.dumps(user_dict))	
						self.response.write("Please Go Back to Main Page")
						self.response.write('<form action="https://final-176617.appspot.com" method="get"><input type="submit" value="Home"></form>')
						
								
						
					#might not need this chunk -->	
					#template_values = {
					#	'first' : first,
					#	'last' : last,
					#	'id' : userID,
					#	'state': statecheck
					#}
			
					#path = os.path.join(os.path.dirname(__file__), 'www/oauth.html') 
					#self.response.out.write(template.render(path, template_values))
					
					#<---
					
				except urlfetch.Error:
					self.response.write('Caught exception fetching url')
						
			except urlfetch.Error:
				self.response.write('Caught exception fetching url')
				
		else:
			self.response.write("error \n")
			self.response.write(state.replace('"', '') + "\n")
			self.response.write(statecheck)
			
	def dispatch(self):
        # Get a session store for this request.
		self.session_store = sessions.get_store(request=self.request)

		try:
            # Dispatch the request.
			webapp2.RequestHandler.dispatch(self)
		finally:
            # Save all sessions.
			self.session_store.save_sessions(self.response)

	@webapp2.cached_property
	def session(self):
        # Returns a session using the default cookie key.
		return self.session_store.get_session()	

		
class TokenHandler(webapp2.RequestHandler):
	def get(self):
		pass

	
class MainPage(webapp2.RequestHandler):
	def get(self):				

		STATE1 = base64.urlsafe_b64encode(uuid.uuid4().bytes)
		STATE2 = STATE1.replace('=', '')
		
		self.session['state'] = STATE2	
		state = self.session.get('state')
		
		#urlAndstate = "https://accounts.google.com/o/oauth2/v2/auth?scope=email&state=" + state + "&redirect_uri=https://assignment4-174116.appspot.com/oauth&response_type=code&client_id=558569944160-kd2rb0gae83j0qhi9a9ov5b4e2hpq4q5.apps.googleusercontent.com"		
		#testinghttps://assignment4-174116.appspot.com/oauth
		urlAndstate = "https://accounts.google.com/o/oauth2/v2/auth?scope=email&access_type=offline&include_granted_scopes=true&state=" + state + "&redirect_uri=https://final-176617.appspot.com/oauth&response_type=code&client_id=733233126010-2ufd7rscja3ps1963h4u4itgc2ef2esm.apps.googleusercontent.com"

		
		template_values = {
			'url' : urlAndstate,
		}
		
		path = os.path.join(os.path.dirname(__file__), 'www/index.html') 
		self.response.out.write(template.render(path, template_values))
		
	def dispatch(self):
        # Get a session store for this request.
		self.session_store = sessions.get_store(request=self.request)

		try:
            # Dispatch the request.
			webapp2.RequestHandler.dispatch(self)
		finally:
            # Save all sessions.
			self.session_store.save_sessions(self.response)

	@webapp2.cached_property
	def session(self):
        # Returns a session using the default cookie key.
		return self.session_store.get_session()	

	  
	  
allowed_methods = webapp2.WSGIApplication.allowed_methods
new_allowed_methods = allowed_methods.union(('PATCH',))
webapp2.WSGIApplication.allowed_methods = new_allowed_methods

config = {}
config['webapp2_extras.sessions'] = {
    'secret_key': 'my-super-secret-key',
}

app = webapp2.WSGIApplication([
    ('/', MainPage),
	('/oauth', OauthHandler),
	('/oauth/token', TokenHandler),
	('/fac', FacHandler),
	('/tex', TexHandler),
	('/user', UserHandler),
	('/tex/del/(.*)', TexDelHandler),
	('/tex/mod/(.*)', TexModHandler),
	('/user/del/(.*)', UserDelHandler),
	('/fac/del/(.*)', FacDelHandler),
	('/fac/inv/(.*)', FacInventoryHandler),
	('/user/admin/(.*)', AdminHandler),
	('/tex/(.*)', TexHandler),
	('/user/(.*)', UserHandler),
	('/fac/(.*)', FacHandler),


],config=config, debug=True)
