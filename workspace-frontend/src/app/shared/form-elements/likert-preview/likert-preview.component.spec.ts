import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LikertPreviewComponent } from './likert-preview.component';

describe('LikertPreviewComponent', () => {
  let component: LikertPreviewComponent;
  let fixture: ComponentFixture<LikertPreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LikertPreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LikertPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
