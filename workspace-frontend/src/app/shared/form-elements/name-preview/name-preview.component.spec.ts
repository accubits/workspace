import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NamePreviewComponent } from './name-preview.component';

describe('NamePreviewComponent', () => {
  let component: NamePreviewComponent;
  let fixture: ComponentFixture<NamePreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NamePreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NamePreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
