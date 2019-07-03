import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ParagraphPreviewComponent } from './paragraph-preview.component';

describe('ParagraphPreviewComponent', () => {
  let component: ParagraphPreviewComponent;
  let fixture: ComponentFixture<ParagraphPreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ParagraphPreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ParagraphPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
