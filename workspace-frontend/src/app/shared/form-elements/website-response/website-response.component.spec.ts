import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WebsiteResponseComponent } from './website-response.component';

describe('WebsiteResponseComponent', () => {
  let component: WebsiteResponseComponent;
  let fixture: ComponentFixture<WebsiteResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WebsiteResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WebsiteResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
