import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WebsiteSubmitComponent } from './website-submit.component';

describe('WebsiteSubmitComponent', () => {
  let component: WebsiteSubmitComponent;
  let fixture: ComponentFixture<WebsiteSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WebsiteSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WebsiteSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
