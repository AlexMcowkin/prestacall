<div class="row mt100">
  <div class="col-lg-12">

      <div class="alert alert-dismissible" role="alert" id="jsMsgBlock">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span id="jsMsg"></span>
      </div>

      <h1>Simplify your links</h1>
      <hr>
      <form class="form-horizontal" method="post" action="" id="shortenform">
        <div class="form-group">
          <label for="inputUrl" class="col-lg-2 control-label">Url</label>
          <div class="col-lg-10">
            <input class="form-control" id="inputUrl" placeholder="Your original URL here..." type="text" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 control-label">Time to live</label>
          <div class="col-lg-10">
            <div class="radio">
              <label><input name="optionsRadios" id="optionsRadios1" value="week" type="radio">1 week</label>
            </div>
            <div class="radio">
              <label><input name="optionsRadios" id="optionsRadios2" value="month" type="radio">1 month</label>
            </div>
            <div class="radio">
              <label><input name="optionsRadios" id="optionsRadios3" value="year" type="radio">1 year</label>
            </div>
            <div class="radio">
              <label><input name="optionsRadios" id="optionsRadios4" value="permanent" type="radio" checked>Permanent</label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-10 col-lg-offset-2">
            <hr>
            <button type="reset" class="btn btn-danger">cancel</button>
            <button type="submit" class="btn btn-primary">shorten URL</button>
          </div>
        </div>
    </form>
  </div>
</div>
<hr>