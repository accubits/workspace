import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PageBreakPreviewComponent } from './page-break-preview.component';

describe('PageBreakPreviewComponent', () => {
  let component: PageBreakPreviewComponent;
  let fixture: ComponentFixture<PageBreakPreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PageBreakPreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PageBreakPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
