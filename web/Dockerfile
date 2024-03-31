# BUILD FRONT-END APP
FROM node:latest as build
WORKDIR /app
COPY . .
RUN npm install
RUN npm run build

# BUILD PROD IMAGE
FROM node:latest as prod 
COPY --from=build /app/.output /app 
WORKDIR /app
CMD ["node", "./server/index.mjs"]
