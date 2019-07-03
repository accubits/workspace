import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WebsitePreviewComponent } from './website-preview.component';

describe('WebsitePreviewComponent', () => {
  let component: WebsitePreviewComponent;
  let fixture: ComponentFixture<WebsitePreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WebsitePreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WebsitePreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
